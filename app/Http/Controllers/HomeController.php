<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\ExtraCharge;
use App\Models\Page;
use App\Models\Surcharge;
use App\Models\Vehicle;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class HomeController extends Controller
{

    public function airport(Request $request)
    {
       $airports = Airport::where('is_active', true)->get();
        return response()->json($airports);
    }
    public function areService(Request $request)
    {
        $area_services = ExtraCharge::where('is_active',true)->get();
        return response()->json($area_services);
    }
    public function capacityLuggage(Request $request)
    {
        $pax = $request->passenger;
        $vehicle = Vehicle::where('is_active', true)
                        ->where('capacity_passenger', '=', $pax)
                        ->orderBy('capacity_passenger', 'asc')
                        ->select('capacity_luggage')
                        ->first();
        $limit = $vehicle ? $vehicle->capacity_luggage : 12;
        return response()->json(['capacity_luggage' => $limit]);
    }
    public function home(Request $request)
    {
        $blogs = BlogPost::where("is_published", true)
                            ->orderBy("published_at", "desc")
                            ->take(3)
                            ->get();
        $cities = City::where('is_featured',true)->orderBy('name', 'asc')->paginate(20);
        $settings = app(GeneralSettings::class);
        $prefilledData = $request->all();
        return view("frontend.app", compact("blogs", "cities", "settings", "prefilledData"));
    }
    public function step2(Request $request)
    {
        $settings = app(GeneralSettings::class);
        $now = Carbon::now();

        // ---------------------------------------------------
        // 1. BOOKING STATUS & SCHEDULE CHECK
        // ---------------------------------------------------
        if ($settings->booking_status === 'closed') {
            return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is currently closed']);
        }

        if ($settings->booking_status === 'scheduled') {
            if ($settings->schedule_type === 'daily') {
                $start = Carbon::createFromFormat('H:i', $settings->daily_start_time);
                $end   = Carbon::createFromFormat('H:i', $settings->daily_end_time);
                if (!$now->between($start, $end)) {
                    return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is closed for now']);
                }
            }
            if ($settings->schedule_type === 'weekly') {
                $today = $now->format('l');
                if (in_array($today, $settings->weekly_off_days ?? [])) {
                    return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is closed today']);
                }
            }
            if ($settings->schedule_type === 'specific_date') {
                $startDate = Carbon::parse($settings->closed_start_date);
                $endDate   = Carbon::parse($settings->closed_end_date);
                if ($now->between($startDate, $endDate)) {
                    return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is temporarily unavailable']);
                }
            }
        }

        // ---------------------------------------------------
        // 2. VALIDATION
        // ---------------------------------------------------
      $request->validate([
            'tripType'     => 'required|in:fromAirport,toAirport,doorToDoor',
            'from_airport' => 'nullable|exists:airports,id',
            'to_airport'   => 'nullable|exists:airports,id',
            'from_address' => 'nullable|string',
            'to_address'   => 'nullable|string',
            'date'         => 'required|date',
            'time'         => 'required',
            'adults'       => 'required|integer|min:1|max:14',
            'luggage'      => 'nullable|integer|min:0',
            'children'     => 'nullable|integer|min:0',
            'booster_seat' => 'nullable|integer|min:0',
            'stopover'     => 'nullable|integer|min:0',
            'pets'         => 'nullable|integer|min:0',
            'front_seat'   => 'nullable|integer|min:0',
            'infant_seat'  => 'nullable|integer|min:0',
        ]);

        try {
            // ---------------------------------------------------
            // 3. DEFINE ORIGIN & DESTINATION
            // ---------------------------------------------------
            $airport = null;
            if ($request->tripType === 'fromAirport') {
                $airport = Airport::findOrFail($request->from_airport);
                $origin = $airport->address;
                $destination = $request->to_address;
            } elseif ($request->tripType === 'toAirport') {
                $airport = Airport::findOrFail($request->to_airport);
                $origin = $request->from_address;
                $destination = $airport->address;
            } else { // doorToDoor
                $origin = $request->from_address;
                $destination = $request->to_address;
            }

            if (!$origin || !$destination) {
                return redirect()->back()->with('notify', ['type' => 'error', 'message' => 'Invalid origin or destination']);
            }

            // ---------------------------------------------------
            // 4. GOOGLE MAPS DISTANCE CALCULATION
            // ---------------------------------------------------
            $apiKey = config('services.google_maps.key');

            // Default distance if API fails (Optional: Remove in production)
            $distanceMiles = 0;

            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins'      => $origin,
                'destinations' => $destination,
                'units'        => 'imperial',
                'key'          => $apiKey,
            ]);
            $data = $response->json();

            if (($data['status'] ?? null) === 'OK' && ($data['rows'][0]['elements'][0]['status'] ?? null) === 'OK') {
                $distanceMiles = round($data['rows'][0]['elements'][0]['distance']['value'] * 0.000621371, 2);
            } else {
                // Log error or handle gracefully
                // \Log::error('Google Maps Error', $data);
                return redirect()->back()->with('notify', ['type' => 'error', 'message' => 'Could not calculate distance. Please check address.']);
            }

            // ---------------------------------------------------
            // 5. COMMON FEES CALCULATION
            // ---------------------------------------------------

            $pickupTax  = $request->tripType === 'fromAirport' ? ($airport->pickup_tax_fee ?? 0) : 0;
            $dropoffTax = $request->tripType === 'toAirport' ? ($airport->dropoff_tax_fee ?? 0) : 0;
            $parkingFee = ($request->tripType === 'fromAirport' || $request->tripType === 'toAirport') ? ($airport->parking_fee ?? 0) : 0;

            $childSeatFee   = ($settings->child_seat_fee ?? 0) * ($request->infant_seat ?? 0);
            $boosterSeatFee = ($settings->booster_seat_fee ?? 0) * ($request->booster_seat ?? 0);
            $stopoverFee    = ($settings->stopover_fee ?? 0) * ($request->stopover ?? 0);
            $petFee    = ($settings->pet_fee ?? $settings->stopover_fee) * ($request->pets ?? 0);
            $frontSeatFee   = ($settings->regular_Seat_rules ?? 0) * ($request->front_seat ?? 0);

            // ZIP Code Logic
            $extractZip = function($address) {
                preg_match('/\b\d{5}(-\d{4})?\b/', $address, $matches);
                return $matches[0] ?? null;
            };

            // Prefer formatted addresses from Google Maps
            $originAddress = $data['origin_addresses'][0] ?? $origin;
            $destinationAddress = $data['destination_addresses'][0] ?? $destination;

            $originZip = $extractZip($originAddress);
            $destinationZip = $extractZip($destinationAddress);

            // return [$originZip, $destinationZip];

            $extraChargeTotal = 0;
           $tollFeeTotal = 0;
           $appliedExtraCharges = []; // ADD THIS
            // Multiplier Logic
            $multiplier = $request->adults > 7 ? 2 : 1;
            if ($originZip || $destinationZip) {
                $extraCharges = ExtraCharge::where('is_active', true)->get();

                foreach ($extraCharges as $charge) {
                    $zipCodes = is_array($charge->zip_codes) ? $charge->zip_codes : json_decode($charge->zip_codes, true);

                    if ($zipCodes && (in_array($originZip, $zipCodes) || in_array($destinationZip, $zipCodes))) {
                        $extraChargeTotal += ($charge->price ?? 0) * $multiplier;
                        $tollFeeTotal += ($charge->toll_fee ?? 0) * $multiplier;

                        $appliedExtraCharges[] = [
                            'name' => $charge->name,
                            'amount' => ($charge->price ?? 0) * $multiplier
                        ];
                    }
                }
            }

            // ---------------------------------------------------
            // 6. CALCULATE FOR ALL ACTIVE VEHICLES
            // ---------------------------------------------------
            $vehicles = Vehicle::where('is_active', 1)->orderBy('capacity_passenger', 'asc')->get();
            $vehicleOptions = [];

            $reqLuggage = (int) ($request->luggage ?? 0);
            $reqPassengers = (int) ($request->adults ?? 0)
                            + ((int) ($request->children ?? 0) );
            $gratuityPercent = (float) ($settings->gratuity_percent ?? 0);

            // Surcharge Preparation
            $bookingTimeStr = Carbon::parse($request->time)->format('H:i:s');
            $bookingDateStr = Carbon::parse($request->date)->format('Y-m-d');
            $activeSurcharges = Surcharge::where('is_active', 1)->get();

            foreach ($vehicles as $vehicle) {
                // A. Base + Distance Fare
                $baseFare = (float) $vehicle->base_fare;
                $minFare  = (float) $vehicle->min_fare;
                $distanceFare = 0;

                foreach ($vehicle->slabs ?? [] as $slab) {
                    if ($distanceMiles >= $slab['start_mile'] && $distanceMiles <= $slab['end_mile']) {
                        $distanceFare = $distanceMiles * (float) $slab['price'];
                        break;
                    }
                }
                $estimatedFare = $baseFare + $distanceFare;
                if ($estimatedFare < $minFare) $estimatedFare = $minFare;

                // B. Surcharges (Must be calculated inside loop as % depends on Fare)
                $surchargeTotal = 0;
                $appliedSurcharges = [];

                foreach ($activeSurcharges as $surcharge) {
                    $isApplicable = false;
                    // Time Check
                    if ($surcharge->type === 'time') {
                        if ($surcharge->start_time > $surcharge->end_time) { // Overnight logic
                            if ($bookingTimeStr >= $surcharge->start_time || $bookingTimeStr <= $surcharge->end_time) $isApplicable = true;
                        } else { // Standard Day
                            if ($bookingTimeStr >= $surcharge->start_time && $bookingTimeStr <= $surcharge->end_time) $isApplicable = true;
                        }
                    }
                    // Date Check
                    elseif ($surcharge->type === 'date') {
                        if ($bookingDateStr >= $surcharge->start_date && $bookingDateStr <= $surcharge->end_date) $isApplicable = true;
                    }

                    if ($isApplicable) {
                        $amountToAdd = ($surcharge->is_percentage == 1)
                            ? ($estimatedFare * $surcharge->price) / 100
                            : $surcharge->price;

                        $surchargeTotal += $amountToAdd;
                        $appliedSurcharges[] = ['name' => $surcharge->name, 'amount' => round($amountToAdd, 2)];
                    }
                }

                // C. Gratuity
                $gratuityFee = round(($estimatedFare * $gratuityPercent) / 100, 2);

                // D. Extra Luggage Logic
                $freeLuggageCapacity = (int) $vehicle->capacity_luggage;

                $extraLuggageCount =max(0, $request->luggage - $reqPassengers);

                $child_seat = ((int) ($request->children ?? 0));

                $extraLuggageFee = $extraLuggageCount * ($settings->luggage_fee ?? 0);

                // E. Final Total
                $totalFare = $estimatedFare + $gratuityFee + $pickupTax + $dropoffTax + $parkingFee +
                            $childSeatFee + $boosterSeatFee + $stopoverFee + $frontSeatFee + $petFee +
                            $extraChargeTotal + $tollFeeTotal + $surchargeTotal + $extraLuggageFee;



                // Store Data
                $vehicleOptions[] = [
                    'vehicle_id'        => $vehicle->id,
                    'name'              => $vehicle->name,
                    'image'             => $vehicle->image,
                    'capacity_passenger'=> $vehicle->capacity_passenger,
                    'capacity_luggage'  => $vehicle->capacity_luggage,
                    'features'          => $vehicle->features ?? ['Luxury'],

                    // Pricing
                    'estimated_fare'    => round($estimatedFare, 2),
                    'gratuity_fee'      => $gratuityFee,
                    'pickup_tax'        => $pickupTax,
                    'dropoff_tax'       => $dropoffTax,
                    'parking_fee'       => $parkingFee,
                    'stopover_fee'      => $stopoverFee,
                    'pet_fee'           => $petFee,
                    'child_seat_fee'    => $childSeatFee,
                    'booster_seat_fee'  => $boosterSeatFee,
                    'front_seat_fee'    => $frontSeatFee,
                    'extra_charges'     => $extraChargeTotal,
                    'toll_fee'          => $tollFeeTotal,
                    'surcharge_fee'     => round($surchargeTotal, 2),
                    'surcharge_details' => $appliedSurcharges,

                    // Luggage
                    'extra_luggage_fee' => $extraLuggageFee,
                    'extra_luggage_count'=> $extraLuggageCount,

                    // Final
                    'total_fare'        => round($totalFare, 2),
                    'pay_cash'          => round($totalFare * 0.9, 2),
                ];
            }

            // ---------------------------------------------------
            // 7. DEFAULT SELECTION & RETURN
            // ---------------------------------------------------
            // Find first vehicle that fits passengers
            $defaultVehicle = collect($vehicleOptions)->first(function($v) use ($reqPassengers) {
                return $v['capacity_passenger'] >= $reqPassengers;
            });

            if (!$defaultVehicle) {
                $defaultVehicle = $vehicleOptions[0] ?? null;
            }

             $child_seat = ((int) ($request->children ?? 0));

            return view('frontend.pages.step2', [
                'trip_type' => $request->tripType,
                'distance_miles' => $distanceMiles,
                'child_seat' => $child_seat,
                'reqPassengers' => $reqPassengers,
                'pickup' => $origin,
                'dropoff' => $destination,
                'request' => $request->all(),
                'vehicleOptions' => $vehicleOptions,
                'defaultVehicle' => $defaultVehicle,
                'extra_charge_details' => $appliedExtraCharges,

                // ERROR FIX: Add this variable
                'vehicles_used' => 1,
            ]);

        } catch (\Exception $e) {
            // Log error for debugging
            // \Log::error($e);
            return redirect()->back()->with('notify', ['type' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function step3(Request $request)
    {
        return view("frontend.pages.step3",compact("request"));
    }
    public function step4(Request $request)
    {
        // dd($request->all());
        return view("frontend.pages.step4",compact("request"));
    }
    public function about(Request $request)
    {
        $settings = app(GeneralSettings::class);
        return view("frontend.pages.about",compact("request","settings"));
    }
    public function childSeat(Request $request)
    {
        $settings = app(GeneralSettings::class);
        return view("frontend.pages.child-seat",compact("request","settings"));
    }
    public function minivan(Request $request)
    {
        $settings = app(GeneralSettings::class);
        return view("frontend.pages.minivan",compact("request","settings"));
    }
    public function areaWeServe(Request $request)
    {
        $cities = City::orderBy('name', 'asc')->paginate(30);
        return view("frontend.pages.area-we-serve",compact("cities"));
    }
    public function serviceDetials($slug)
    {
        $page = Page::where('is_active',true)->where("slug",$slug)->first();
        if(!$page){
            return back();
        }
         $settings = app(GeneralSettings::class);
        return view("frontend.pages.service_details",compact("page","settings"));
    }
    public function contact(Request $request)
    {
        return view("frontend.pages.contact",compact("request"));
    }
    public function paymentPolicy(Request $request)
    {
        return view("frontend.pages.payment_policy",compact("request"));
    }
     public function termsConditions(Request $request)
    {
        return view("frontend.pages.term_conditions",compact("request"));
    }
     public function privacyPolicy(Request $request)
    {
        return view("frontend.pages.privacy_policy",compact("request"));
    }
    public function blogs(Request $request)
    {
        $blogs = BlogPost::where("is_published", true)
                         ->orderBy("published_at", "desc")
                         ->paginate(10);
        return view("frontend.pages.blog",compact("blogs"));
    }
    public function blogDetails($slug)
    {
        $blog = BlogPost::where('slug',$slug)->first();
        return view("frontend.pages.blog_details",compact("blog"));
    }
    public function confirmBooking(Request $request)
    {
        // ======================================================
        // STEP 1: INPUT VALIDATION
        // ======================================================
        $request->validate([
            'stripe_token'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);
        $token         = $request->stripe_token;
        $amountCharged = (float) $request->amount_charged;
        $booking       = null;

        // ======================================================
        // STEP 2: SAVE DATA FIRST (DATABASE TRANSACTION)
        // ======================================================
        DB::beginTransaction();
        try {

            $lastBooking = Booking::lockForUpdate()->orderBy('id', 'desc')->first();
            $lastNumber = 0;
            if ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches)) {
                $lastNumber = (int) $matches[1];
            }
            $bookingNo = 'BLAT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $booking = new Booking();
            $booking->booking_no = $bookingNo;

            // --- Passenger Info ---
            $booking->passenger_name     = $request->passenger_name;
            $booking->passenger_email    = $request->passenger_email;
            $booking->passenger_phone    = $request->phone_number;
            $booking->phone_country_code = $request->phone_country_code;
            $booking->alternate_phone    = $request->alternate_phone;
            $booking->mailing_address    = $request->mailing_address;
            $booking->special_needs      = $request->special_needs;

            // --- Trip Info ---
            $booking->trip_type       = $request->trip_type;
            $booking->pickup_date     = Carbon::parse($request->date)->format('Y-m-d');
            $booking->pickup_time     = $request->time;
            $booking->pickup_address  = $request->pickup ?? $request->fromAddress;
            $booking->dropoff_address = $request->dropoff ?? $request->to_address;
            $booking->distance_miles  = $request->distance_miles ?? 0;

            // --- Flight & Vehicle ---
            $fare = $request->fare ?? [];
            $booking->airline_name    = $request->airline_name;
            $booking->flight_number   = $request->flight_number;
            $booking->vehicle_id      = $request->vehicle_id;
            $booking->vehicle_type    = $fare['name'] ?? 'Unknown';
            $booking->vehicles_used   = $request->vehicles_used ?? 1;

            // --- Counts ---
            $booking->adults           = $request->adults ?? 0;
            $booking->children         = $request->child_seat ?? 0;
            $booking->total_passengers = $request->reqPassengers;
            $booking->luggage          = $request->luggage ?? 0;

            // --- Extras ---
            $booking->booster_seat_count = $request->booster_seat ?? 0;
            $booking->infant_seat_count  = $request->infant_seat ?? 0;
            $booking->front_seat_count   = $request->front_seat ?? 0;
            $booking->stopover_count     = $request->stopover ?? 0;
            $booking->pet_count          = $request->pets ?? 0;

            // --- Billing ---
            $booking->card_holder_name = $request->card_holder_name;
            $booking->billing_phone    = $request->billing_phone;
            $booking->billing_address  = $request->billing_address;
            $booking->billing_city     = $request->billing_city;
            $booking->billing_state    = $request->billing_state;
            $booking->billing_zip      = $request->billing_zip;

            // --- Fees ---
            $booking->estimated_fare    = $fare['estimatedFare'] ?? 0;
            $booking->gratuity          = $fare['gratuity'] ?? 0;
            $booking->pickup_tax        = $fare['pickup_tax'] ?? 0;
            $booking->dropoff_tax       = $fare['dropoff_tax'] ?? 0;
            $booking->parking_fee       = $fare['parking_fee'] ?? 0;
            $booking->toll_fee          = $fare['toll_fee'] ?? 0;
            $booking->surcharge_fee     = $fare['surcharge_fee'] ?? 0;
            $booking->extra_luggage_fee = $fare['extra_luggage_fee'] ?? 0;
            $booking->extras_total      = $request->extras_total ?? 0;
            $booking->child_seat_fee    = $fare['child_seat_fee'] ?? 0;
            $booking->booster_seat_fee  = $fare['booster_seat_fee'] ?? 0;
            $booking->front_seat_fee    = $fare['front_seat_fee'] ?? 0;
            $booking->stopover_fee      = $fare['stopover_fee'] ?? 0;
            $booking->surcharge_details = $request->surcharge_details ?? [];
            $booking->extra_charge_details = $request->extra_charge_details ?? [];

            // --- Payment Initials ---
            $booking->total_fare     = isset($fare['total']) ? (float) $fare['total'] : 0;
            $booking->paid_amount    = 0;
            $booking->due_amount     = $booking->total_fare;
            $booking->payment_method = 'stripe';
            $booking->status         = 'pending';
            $booking->payment_status = 'unpaid';

            $booking->save();
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Database Save Error: ' . $e->getMessage());

            return back()->with('notify', [
                'type' => 'error',
                'message' => 'System Error: Could not initiate booking. Please try again. (No money was charged)'
            ])->withInput();
        }
        // ======================================================
        // STEP 3: STRIPE PAYMENT PROCESSING
        // ======================================================
        $paymentIntent = null;
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) round($amountCharged * 100),
                'currency' => 'usd',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => ['token' => $token],
                ],
                'confirmation_method' => 'manual',
                'capture_method' => 'manual',
                'confirm' => true,

                // --- FIX: Redirect Error Solution ---
                'return_url' => route('home'),

                'description' => 'Booking: ' . $booking->booking_no,
                'receipt_email' => $request->passenger_email,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'phone' => $request->phone_number
                ]
            ]);
            $cardBrand = null;
            $cardLast4 = null;
            if (isset($paymentIntent->charges->data[0])) {
                $charge = $paymentIntent->charges->data[0];
                $cardBrand = $charge->payment_method_details->card->brand ?? null;
                $cardLast4 = $charge->payment_method_details->card->last4 ?? null;
            }

            // ==================================================
            // STEP 4: SUCCESS - CAPTURE & UPDATE
            // ==================================================
            $paymentIntent->capture();
            $booking->transaction_id = $paymentIntent->id;
            $booking->paid_amount    = $amountCharged;
            $booking->due_amount     = max(0, $booking->total_fare - $amountCharged);
            $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
            $booking->status         = 'confirmed';
            $booking->card_brand     = $cardBrand;
            $booking->card_last_four = $cardLast4;
            $booking->save();
            try {
                Mail::to(config('mail.from.address'))->send(new BookingConfirmationMail($booking));
                Mail::to($booking->passenger_email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                Log::error('Mail Error: ' . $e->getMessage());
            }

            return redirect()->route('home', [
                'payment' => 'success',
                'booking' => $booking->booking_no
            ])->with('notify', [
                'type' => 'success',
                'message' => 'Payment successful! Booking confirmed.'
            ]);

        } catch (\Throwable $e) {
            // ==================================================
            // STEP 5: FAILURE & SAFETY NET (REFUND LOGIC)
            // ==================================================
            Log::error('Stripe/System Error: ' . $e->getMessage());

            if($booking) {
                $booking->status = 'failed';
                $booking->payment_status = 'failed';
                $booking->save();
            }
            try {
                $failData = [
                    'name' => $request->passenger_name,
                    'email' => $request->passenger_email,
                    'phone' => $request->phone_number,
                    'error_message' => $e->getMessage(),
                    'date' => now()->toDateTimeString()
                ];
                Mail::to(config('mail.from.address'))->send(new PaymentFailedMail($failData));
            } catch (\Exception $ex) {}

            return back()->with('notify', [
                'type' => 'error',
                'message' => 'Payment Failed: '. 'If charged, it will be refunded automatically.'
            ])->withInput();
        }
    }
}

