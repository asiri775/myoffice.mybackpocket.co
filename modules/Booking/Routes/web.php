<?php

use Illuminate\Support\Facades\Route;
// Booking
Route::group(['prefix' => config('booking.booking_route_prefix')], function () {
    Route::post('/addToCart', 'BookingController@addToCart')->name('booking.addToCart');
    Route::post('/doCheckout', 'BookingController@doCheckout')->name('booking.doCheckout');
    Route::get('/confirm/{gateway}', 'BookingController@confirmPayment');
    Route::get('/cancel/{gateway}', 'BookingController@cancelPayment');
    Route::get('/{code}', 'BookingController@detail');
    Route::get('/{code}/checkout', 'BookingController@checkout')->name('booking.checkout');
    Route::get('/{code}/check-status', 'BookingController@checkStatusCheckout');

    //ical
    Route::get('/export-ical/{type}/{id}', 'BookingController@exportIcal')->name('booking.admin.export-ical');
    //inquiry
    Route::post('/addEnquiry', 'BookingController@addEnquiry');
    Route::post('/setPaidAmount', 'BookingController@setPaidAmount');

    Route::post('/booking/update-mass', 'BookingController@updateMassBooking')->name('booking.updateMassBooking');
    Route::post('/booking/update-single', 'BookingController@updateSingleBooking')->name('booking.updateSingleBooking');
});


Route::group(['prefix' => 'gateway'], function () {
    Route::get('/confirm/{gateway}', 'NormalCheckoutController@confirmPayment')->name('gateway.confirm');
    Route::get('/cancel/{gateway}', 'NormalCheckoutController@cancelPayment')->name('gateway.cancel');
    Route::get('/info', 'NormalCheckoutController@showInfo')->name('gateway.info');
});
