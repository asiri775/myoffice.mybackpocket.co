<?php
use Illuminate\Support\Facades\Route;
Route::get('/','MediaController@index')->name('media.admin.index');

Route::post('/edit_image','MediaController@editImage')->name('media.edit.image');
