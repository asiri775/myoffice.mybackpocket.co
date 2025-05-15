<?php
use Illuminate\Support\Facades\Route;

//Review
Route::group(['middleware' => ['auth']],function(){
    Route::get('/review',function (){ return redirect('/'); });
    Route::post('/review','ReviewController@addReview')->name('review.store');
    Route::get('/user/reviews','ReviewController@index')->name('reviews.vendor.index');
    Route::get('/user/reviews/update-status/{id}','ReviewController@updateStatus')->name('reviews.vendor.updateStatus');
    Route::post('/user/reviews/datatable', 'ReviewController@datatable')->name('reviews.vendor.datatable');
});
