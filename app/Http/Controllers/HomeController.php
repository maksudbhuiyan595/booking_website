<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\ExtraCharge;
use App\Models\Vehicle;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function airport(Request $request)
    {
        $airport = Airport::where('is_active',true)->get();
        return response()->json($airport);
    }
    public function home()
    {
        return view("frontend.app");
    }
    public function step2(Request $request)
    {
        // ---------------- VALIDATION ----------------
        $request->validate([
            'tripType'     => 'required|in:fromAirport,toAirport,doorToDoor',
            'from_airport' => 'nullable|exists:airports,id',
            'to_airport'   => 'nullable|exists:airports,id',
            'from_address' => 'nullable|string',
            'to_address'   => 'nullable|string',
            'adults'       => 'required|integer|min:1|max:12',
            'luggage'      => 'nullable|integer|min:0',
            'childen'      => 'nullable|integer|min:0',
            'booster_seat' => 'nullable|integer|min:0',
            'stopover'     => 'nullable|integer|min:0',
            'front_seat'   => 'nullable|integer|min:0',
        ]);

        // ---------------- ORIGIN & DESTINATION ----------------
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
            return response()->json(['error' => 'Invalid origin or destination'], 422);
        }

        // ---------------- DISTANCE (MILES) ----------------
        $response = Http::get(
            'https://maps.googleapis.com/maps/api/distancematrix/json',
            [
                'origins'      => $origin,
                'destinations' => $destination,
                'units'        => 'imperial',
                'key'          => config('services.google_maps.key'),
            ]
        );

        $data = $response->json();

        if (
            ($data['status'] ?? null) !== 'OK' ||
            ($data['rows'][0]['elements'][0]['status'] ?? null) !== 'OK'
        ) {
            return response()->json(['error' => 'Distance not found'], 422);
        }

        $distanceMiles = round(
            $data['rows'][0]['elements'][0]['distance']['value'] * 0.000621371,
            2
        );

        // ---------------- VEHICLE SELECTION ----------------
        $vehicles = Vehicle::where('is_active', true)
            ->orderBy('capacity_passenger', 'desc')
            ->get();

        $remaining = $request->adults;
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

        // ---------------- FARE CALCULATION ----------------
        $distanceFare = 0;
        $baseFare = 0;

        foreach ($selectedVehicles as $vehicle) {
            $baseFare += $vehicle->base_fare;

            foreach ($vehicle->slabs ?? [] as $slab) {
                if ($distanceMiles >= $slab['start_mile'] && $distanceMiles <= $slab['end_mile']) {
                    $distanceFare += $distanceMiles * $slab['price'];
                    break;
                }
            }
        }

        // ---------------- EXTRA FEES ----------------
        $settings = app(GeneralSettings::class);

        $estimatedFare = $distanceFare + $baseFare;
        $gratuityFee = ($estimatedFare * ($settings->gratuity_percent ?? 0)) / 100;

        $extraLuggage = max(0, ($request->luggage ?? 0) - $request->adults);
        $extraLuggageFee = ($settings->luggage_fee ?? 0) * $extraLuggage;

        $childSeatFee   = ($settings->child_seat_fee ?? 0) * ($request->childen ?? 0);
        $boosterSeatFee = ($settings->booster_seat_fee ?? 0) * ($request->booster_seat ?? 0);
        $stopoverFee    = ($settings->stopover_fee ?? 0) * ($request->stopover ?? 0);
        $frontSeatFee   = ($settings->front_seat_fee ?? 0) * ($request->front_seat ?? 0);

        $pickupTax  = $request->tripType === 'fromAirport' ? ($airport->pickup_tax_fee ?? 0) : 0;
        $dropoffTax = $request->tripType === 'toAirport' ? ($airport->dropoff_tax_fee ?? 0) : 0;
        $parkingFee = ($request->tripType === 'fromAirport' || $request->tripType === 'toAirport') ? ($airport->parking_fee ?? 0) : 0;

        // ---------------- EXTRA CHARGES (ZIP BASED) ----------------
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

        // ---------------- TOTAL ----------------
        $totalFare =
            $distanceFare +
            $baseFare +
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
            $tollFeeTotal;

        return view('frontend.pages.step2', [
            'trip_type' => $request->tripType,
            'distance_miles' => $distanceMiles,
            'vehicles_used' => count($selectedVehicles),
            'pickup' => $origin,
            'dropoff' => $destination,
            'fare' => [
                'base_fare'        => round($baseFare, 2),
                'distance_fare'    => round($distanceFare, 2),
                'gratuity'         => round($gratuityFee, 2),
                'pickup_tax'       => $pickupTax,
                'dropoff_tax'      => $dropoffTax,
                'parking_fee'      => $parkingFee,
                'child_seat_fee'   => $childSeatFee,
                'booster_seat_fee' => $boosterSeatFee,
                'stopover_fee'     => $stopoverFee,
                'front_seat_fee'   => $frontSeatFee,
                'extra_luggage_fee'=> $extraLuggageFee,
                'extra_charges'    => $extraChargeTotal,
                'toll_fee'         => $tollFeeTotal,
                'total'            => round($totalFare, 2),
            ],
            'request' => $request->all(),
            'extra_charge_details' => $appliedExtraCharges,
        ]);
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
        return view("frontend.pages.area-we-serve",compact("request"));
    }
    public function contact(Request $request)
    {
        return view("frontend.pages.contact",compact("request"));
    }
}
