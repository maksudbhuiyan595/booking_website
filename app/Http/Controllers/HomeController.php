<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\ExtraCharge;
use App\Models\Surcharge;
use App\Models\Vehicle;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    public function airport(Request $request)
    {
        $airport = Airport::where('is_active',true)->get();
        return response()->json($airport);
    }
    public function areService(Request $request)
    {
        $area_services = ExtraCharge::where('is_active',true)->get();
        return response()->json($area_services);
    }
    public function capacityLuggage(Request $request)
    {
        $vehicle = Vehicle::where('is_active', true)
                        ->where('capacity_passenger', $request->passenger)
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
        $cities = City::orderBy('name', 'asc')->paginate(12);
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
            // Daily Schedule
            if ($settings->schedule_type === 'daily') {
                $start = Carbon::createFromFormat('H:i', $settings->daily_start_time);
                $end   = Carbon::createFromFormat('H:i', $settings->daily_end_time);
                if (!$now->between($start, $end)) {
                    return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is closed for now']);
                }
            }
            // Weekly Schedule
            if ($settings->schedule_type === 'weekly') {
                $today = $now->format('l');
                if (in_array($today, $settings->weekly_off_days ?? [])) {
                    return redirect()->back()->with('notify', ['type' => 'error', 'message' => $settings->closing_message ?? 'Booking is closed today']);
                }
            }
            // Specific Date Schedule
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
            'front_seat'   => 'nullable|integer|min:0',
            'infant_seat'  => 'nullable|integer|min:0',
        ]);

        try {
            // ---------------------------------------------------
            // 3. DEFINE ORIGIN & DESTINATION
            // ---------------------------------------------------
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
                $airport = null;
            }

            if (!$origin || !$destination) {
                return redirect()->back()->with('notify', ['type' => 'error', 'message' => 'Invalid origin or destination']);
            }

            // ---------------------------------------------------
            // 4. GOOGLE MAPS DISTANCE CALCULATION
            // ---------------------------------------------------
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins'      => $origin,
                'destinations' => $destination,
                'units'        => 'imperial',
                'key'          => config('services.google_maps.key'),
            ]);

            $data = $response->json();

            if (($data['status'] ?? null) !== 'OK' || ($data['rows'][0]['elements'][0]['status'] ?? null) !== 'OK') {
                return redirect()->back()->with('notify', ['type' => 'error', 'message' => 'Distance calculation failed. Please check address.']);
            }

            $distanceMiles = round($data['rows'][0]['elements'][0]['distance']['value'] * 0.000621371, 2);

            // ---------------------------------------------------
            // 5. VEHICLE SELECTION LOGIC
            // ---------------------------------------------------
            $vehicles = Vehicle::where('is_active', true)->orderBy('capacity_passenger', 'desc')->get();
            $remaining = $request->adults + ($request->children ?? 0);
            $selectedVehicles = [];

            foreach ($vehicles as $vehicle) {
                while ($remaining >= $vehicle->capacity_passenger) {
                    $selectedVehicles[] = $vehicle;
                    $remaining -= $vehicle->capacity_passenger;
                }
            }

            if ($remaining > 0 && $vehicles->count()) {
                $selectedVehicles[] = $vehicles->last();
            }

            // ---------------------------------------------------
            // 6. BASE FARE & DISTANCE SLABS CALCULATION
            // ---------------------------------------------------
            $distanceFare = 0;
            $baseFare = 0;
            $capacity_passenger = 0;
            $capacity_luggage = 0;

            foreach ($selectedVehicles as $vehicle) {
                $baseFare += $vehicle->base_fare;
                $capacity_luggage += $vehicle->capacity_luggage;
                $capacity_passenger += $vehicle->capacity_passenger;
                foreach ($vehicle->slabs ?? [] as $slab) {
                    if ($distanceMiles >= $slab['start_mile'] && $distanceMiles <= $slab['end_mile']) {
                        $distanceFare += $distanceMiles * $slab['price'];
                        break;
                    }
                }
            }
            $estimatedFare = $distanceFare + $baseFare; // Total Ride Cost (Before Extras)

            // ---------------------------------------------------
            // 7. STANDARD EXTRA FEES
            // ---------------------------------------------------
            $gratuityFee = ($estimatedFare * ($settings->gratuity_percent ?? 0)) / 100;

            // Luggage calculation (assuming vehicle cap is handled elsewhere or simplified here)
            $extraLuggage = max(0, ($request->luggage ?? 0) - ($request->adults + $request->children ?? 0));
            $extraLuggageFee = ($settings->luggage_fee ?? 0) * $extraLuggage; // Or your specific logic

            $childSeatFee   = ($settings->child_seat_fee ?? 0) * ($request->infant_seat ?? 0);
            $boosterSeatFee = ($settings->booster_seat_fee ?? 0) * ($request->booster_seat ?? 0);
            $stopoverFee    = ($settings->stopover_fee ?? 0) * ($request->stopover ?? 0);
            $frontSeatFee   = ($settings->front_seat_fee ?? 0) * ($request->front_seat ?? 0);

            $pickupTax  = $request->tripType === 'fromAirport' ? ($airport->pickup_tax_fee ?? 0) : 0;
            $dropoffTax = $request->tripType === 'toAirport' ? ($airport->dropoff_tax_fee ?? 0) : 0;
            $parkingFee = ($request->tripType === 'fromAirport' || $request->tripType === 'toAirport') ? ($airport->parking_fee ?? 0) : 0;

            // ---------------------------------------------------
            // 8. ZIP CODE EXTRA CHARGES
            // ---------------------------------------------------
            $extractZip = function($address) {
                preg_match('/\b\d{5}\b/', $address, $matches);
                return $matches[0] ?? null;
            };
            $originZip = $extractZip($origin);
            $destinationZip = $extractZip($destination);

            $extraChargeTotal = 0;
            $tollFeeTotal = 0;
            $appliedExtraCharges = [];

            if ($originZip || $destinationZip) {
                $extraCharges = ExtraCharge::where('is_active', true)->get();
                foreach ($extraCharges as $charge) {
                    $zipCodes = is_array($charge->zip_codes) ? $charge->zip_codes : json_decode($charge->zip_codes, true);
                    if (!$zipCodes) continue;

                    if (in_array($originZip, $zipCodes) || in_array($destinationZip, $zipCodes)) {
                        $extraChargeTotal += $charge->price ?? 0;
                        $tollFeeTotal += $charge->toll_fee ?? 0;
                        $appliedExtraCharges[] = [
                            'name' => $charge->name,
                            'price' => $charge->price,
                            'toll_fee' => $charge->toll_fee,
                        ];
                    }
                }
            }

            // ---------------------------------------------------
            // 9. SURCHARGES CALCULATION (NEW)
            // ---------------------------------------------------
            $bookingTimeStr = Carbon::parse($request->time)->format('H:i:s');
            $bookingDateStr = Carbon::parse($request->date)->format('Y-m-d');

            $surcharges = Surcharge::where('is_active', 1)->get();
            $surchargeTotal = 0;
            $appliedSurcharges = [];

            foreach ($surcharges as $surcharge) {
                $isApplicable = false;

                // A. Time Based Check
                if ($surcharge->type === 'time' && $surcharge->start_time && $surcharge->end_time) {
                    $sStart = $surcharge->start_time;
                    $sEnd   = $surcharge->end_time;

                    if ($sStart > $sEnd) {
                        // Overnight (e.g., 22:00 to 06:00)
                        if ($bookingTimeStr >= $sStart || $bookingTimeStr <= $sEnd) {
                            $isApplicable = true;
                        }
                    } else {
                        // Standard Day (e.g., 10:00 to 16:00)
                        if ($bookingTimeStr >= $sStart && $bookingTimeStr <= $sEnd) {
                            $isApplicable = true;
                        }
                    }
                }

                // B. Date/Holiday Based Check
                elseif ($surcharge->type === 'date' && $surcharge->start_date && $surcharge->end_date) {
                    if ($bookingDateStr >= $surcharge->start_date && $bookingDateStr <= $surcharge->end_date) {
                        $isApplicable = true;
                    }
                }

                // C. Calculate Amount
                if ($isApplicable) {
                    $amountToAdd = 0;
                    $priceValue = floatval($surcharge->price);

                    if ($surcharge->is_percentage == 1) {
                        // Percentage of (Base Fare + Distance Fare)
                        $amountToAdd = ($estimatedFare * $priceValue) / 100;
                    } else {
                        // Fixed Price
                        $amountToAdd = $priceValue;
                    }

                    $surchargeTotal += $amountToAdd;
                    $appliedSurcharges[] = [
                        'name' => $surcharge->name,
                        'amount' => round($amountToAdd, 2)
                    ];
                }
            }

            // ---------------------------------------------------
            // 10. FINAL TOTAL
            // ---------------------------------------------------
            $totalFare =
                $estimatedFare +      // Base + Distance
                $gratuityFee +
                $pickupTax +
                $dropoffTax +
                $parkingFee +
                $childSeatFee +
                $boosterSeatFee +
                $stopoverFee +
                $frontSeatFee +
                $extraLuggageFee +
                $extraChargeTotal +
                $tollFeeTotal +
                $surchargeTotal;      // Added Surcharge

            return view('frontend.pages.step2', [
                'trip_type' => $request->tripType,
                'distance_miles' => $distanceMiles,
                'vehicles_used' => count($selectedVehicles),
                'pickup' => $origin,
                'dropoff' => $destination,
                'fare' => [
                    // 'base_fare'       => round($baseFare, 2),
                    // 'distance_fare'   => round($distanceFare, 2),
                    'capacity_luggage'       => $capacity_luggage,
                    'capacity_passenger'   => $capacity_passenger,
                    'estimatedFare'   => round($estimatedFare, 2),
                    'gratuity'        => round($gratuityFee, 2),
                    'pickup_tax'      => $pickupTax,
                    'dropoff_tax'     => $dropoffTax,
                    'parking_fee'     => $parkingFee,
                    'child_seat_fee'  => $childSeatFee,
                    'booster_seat_fee'=> $boosterSeatFee,
                    'stopover_fee'    => $stopoverFee,
                    'front_seat_fee'  => $frontSeatFee,
                    'extra_luggage_fee' => $extraLuggageFee,
                    'extra_charges'   => $extraChargeTotal,
                    'toll_fee'        => $tollFeeTotal,
                    'surcharge_fee'   => round($surchargeTotal, 2),
                    'total'           => round($totalFare, 2),
                ],
                'request' => $request->all(),
                'extra_charge_details' => $appliedExtraCharges,
                'surcharge_details'    => $appliedSurcharges,
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('notify', [
                'type' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage()
            ]);
        }
    }
    public function step3(Request $request)
    {
        // dd($request->all());
        return view("frontend.pages.step3",compact("request"));
    }
    public function step4(Request $request)
    {
        return view("frontend.pages.step4",compact("request"));
    }
    public function about(Request $request)
    {
        return view("frontend.pages.about",compact("request"));
    }
    public function childSeat(Request $request)
    {
        return view("frontend.pages.child-seat",compact("request"));
    }
    public function minivan(Request $request)
    {
        return view("frontend.pages.minivan",compact("request"));
    }
    public function areaWeServe(Request $request)
    {
        $cities = City::orderBy('name', 'asc')->paginate(12);
        return view("frontend.pages.area-we-serve",compact("cities"));
    }
    public function serviceDetials($slug)
    {
        $city = City::where("slug",$slug)->first();
        return view("frontend.pages.service_details",compact("city"));
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
}

