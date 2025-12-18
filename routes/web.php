<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/step2', 'step2')->name('step2');
    Route::get('/step3', 'step3')->name('step3');
    Route::get('/step4', 'step4')->name('step4');
    Route::get('/about', 'about')->name('about');
    Route::get('/child-seat', 'childSeat')->name('child.seat');
    Route::get('/minivan/suv', 'minivan')->name('minivan');
    Route::get('/area-we-serve', 'areaWeServe')->name('area.we.serve');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/airports', 'airport')->name('airports');
});
Route::controller(BookingController::class)->group(function () {
    Route::post('/book-confirm', 'confirmBooking')->name('book.confirm');

});

Route::get('/payment', [BookingController::class, 'showForm']);
Route::post('/payment', [BookingController::class, 'processPayment'])->name('payment.process');
