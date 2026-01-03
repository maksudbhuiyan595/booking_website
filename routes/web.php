<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    return generate_sitemap_xml();
});
Route::controller(HomeController::class)->group(function () {
    Route::get('/airports', 'airport')->name('airports');
    Route::get('/capacity-luggage', 'capacityLuggage')->name('luggage.capacity');
    Route::get('/', 'home')->name('home');
    Route::get('/step2', 'step2')->name('step2');
    Route::get('/step3', 'step3')->name('step3');
    Route::get('/step4', 'step4')->name('step4');
    Route::get('about/', 'about')->name('about');
    Route::get('/taxi-infant-car-seat', 'childSeat')->name('child.seat');
    Route::get('/minivan-taxi-service', 'minivan')->name('minivan');
    Route::get('/area-we-serve', 'areaWeServe')->name('area.we.serve');
    Route::get('/contact', 'contact')->name('contact');

    Route::get('/blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy.policy');
    Route::get('/terms&conditions', 'termsConditions')->name('term.conditions');
    Route::get('/payment-policy', 'paymentPolicy')->name('payment.policy');

    Route::get('/services', 'areService')->name('area.service');
    Route::get('/{slug}', 'serviceDetials')->name('service.details');
    Route::get('/setting', 'setting')->name('setting');
});
Route::controller(BookingController::class)->group(function () {
    Route::post('/book-confirm', 'confirmBooking')->name('book.confirm');
});


