<?php

use Illuminate\Support\Facades\Route;
use Modules\Gallary\Http\Controllers\GallaryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('gallary', GallaryController::class)->names('gallary');
});
