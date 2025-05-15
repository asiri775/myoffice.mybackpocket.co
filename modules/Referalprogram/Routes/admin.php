<?php
use \Illuminate\Support\Facades\Route;
Route::get('/','ReferalprogramController@index')->name('referal-program.admin.index');
Route::get('/create','ReferalprogramController@create')->name('referal-program.admin.create');
Route::get('/edit/{id}','ReferalprogramController@edit')->name('referal-program.admin.edit');
Route::post('/store/{id}','ReferalprogramController@store')->name('referal-program.admin.store');
Route::post('/bulkEdit','ReferalprogramController@bulkEdit')->name('referal-program.admin.bulkEdit');