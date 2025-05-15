<?php

use App\Helpers\ImportHelper;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('fix-zip', function () {
    ImportHelper::fixZipCode();
})->describe('Fixing Zip Codes');


Artisan::command('import-data {range}', function ($range) {
    $range = trim($range);
    $data = explode('-', $range);
    ImportHelper::importData(intval($data[0]), intval($data[1]));
})->describe('Importing Data');
