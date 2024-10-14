<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\Http\Controllers\LocationController;

use Modules\Location\Http\Controllers\api\user\Nationality\NationalityController;
use Modules\Location\Http\Controllers\api\admin\Nationality\AdminNationalityController;
use Modules\Location\Http\Controllers\api\admin\country\AdminCountryController;
use Modules\Location\Http\Controllers\api\user\country\countryController;

Route::group(['prefix' => 'nationality'], function () {
    Route::get("/search", [NationalityController::class, "search"]);
    Route::get("/countries", [NationalityController::class, "getCountries"]);
    Route::get("/", [NationalityController::class, "index"]);
    Route::post("/store", [AdminNationalityController::class, "store"]);
    Route::put("/update", [AdminNationalityController::class, "update"]);
    Route::delete("/destroy", [AdminNationalityController::class, "destroy"]);
});

Route::group(['prefix' => 'country'], function () {
    Route::get("/states", [countryController::class, "getStates"]);
    Route::get("/search", [countryController::class, "search"]);
    Route::get("/", [CountryController::class, "index"]);
    Route::post("/store", [AdminCountryController::class, "store"]);
    Route::put("/update", [AdminCountryController::class, "update"]);
    Route::delete("/destroy", [AdminCountryController::class, "destroy"]);
});
