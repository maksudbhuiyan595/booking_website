<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view("frontend.app");
    }
   public function step2(Request $request)
{
    // Convert
    $adults   = (int) $request->adults;
    $children = (int) $request->children;
    $luggage  = (int) $request->luggage;

    // Total persons
    $total_persons = $adults + $children;

    // Free luggage logic
    if ($total_persons <= 3) {
        $free_luggage = 3;  // For 1–3 persons => 3 free bags
    } else {
        $free_luggage = $total_persons; // For 4+ persons => 1 bag per person
    }

    // Extra luggage
    $extra_luggage = max(0, $luggage - $free_luggage);

    // Extra luggage cost = $10 each
    $extra_luggage_cost = $extra_luggage * 10;

    // Merge calculation into request so frontend can use it directly
    $request->merge([
        "total_persons"        => $total_persons,
        "free_luggage"         => $free_luggage,
        "extra_luggage"        => $extra_luggage,
        "extra_luggage_cost"   => $extra_luggage_cost,
    ]);

    // Return view with the same request including calculation
    return view("frontend.pages.step2", compact("request"));
}

     public function step3(Request $request)
    {
        return view("frontend.pages.step3",compact("request"));
    }
      public function step4(Request $request)
    {
        return view("frontend.pages.step4",compact("request"));
    }
    public function airport(Request $request)
    {
        $airport = Airport::where('is_active',true)->get();
        return response()->json($airport);
    }
}
