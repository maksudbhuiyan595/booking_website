<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Vehicle;
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
        $distanceMiles = null;
        $distanceText = null;
        $durationText = null;

        if ($request->tripType === "fromAirport") {
            // Validate required input
            $request->validate([
                'from_airport' => 'required|exists:airports,id',
                'to_address' => 'required|string',
            ]);

            $airport = Airport::findOrFail($request->from_airport);
            $origin = urlencode($airport->address); // urlencode for safe URL
            $destination = urlencode($request->to_address);

            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origin}&destinations={$destination}&key={$apiKey}&units=imperial";

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK' && isset($data['rows'][0]['elements'][0])) {
                $element = $data['rows'][0]['elements'][0];

                if ($element['status'] === 'OK') {
                    $distanceText = $element['distance']['text'];
                    $durationText = $element['duration']['text'];
                    $distanceMiles = $element['distance']['value'] * 0.000621371; // meters to miles
                }
            }
        }
        // dd($request->all());

        $vechcle = Vehicle::where('is_active',true)->first();

        $total_pesenger = $request->adults + $request->seats_dummy;
        $DistanceCovered = $distanceMiles;
        $BaseFare = $total_pesenger > 7 ? $vechcle->base_fare *2 : $vechcle->base_fare;
        $MinimumFare = $total_pesenger > 7 ? $vechcle->minimum_fare *2 : $vechcle->minimum_fare;
        $airport_toll_tax = $total_pesenger > 7 ? $vechcle->minimum_fare *2 : $vechcle->minimum_fare;


        if($total_pesenger > 12) {
            dd("You can not book");
        }

    }


    public function step3(Request $request)
    {
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
