<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view("frontend.app");
    }
    public function step2(Request $request)
    {

        return view("frontend.pages.step2",compact("request"));
    }
     public function step3(Request $request)
    {
        return view("frontend.pages.step3",compact("request"));
    }
      public function step4(Request $request)
    {
        return view("frontend.pages.step4",compact("request"));
    }


}
