<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('frontend.app');
// });


Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/step2', 'step2')->name('step2');
    Route::get('/step3', 'step3')->name('step3');
    Route::get('/step4', 'step4')->name('step4');

    // Route::get('/about', 'about')->name('about');
    // Route::get('/contact', 'contact')->name('contact');
});
