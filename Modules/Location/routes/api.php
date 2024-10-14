<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\Http\Controllers\LocationController;

use Modules\Location\Http\Controllers\api\user\Nationality\NationalityController;
use Modules\Location\Http\Controllers\api\admin\Nationality\AdminNationalityController;

Route::group(['prefix' => 'nationality'], function () {
    Route::get("/search", [NationalityController::class, "search"]);
    Route::get("/countries", [NationalityController::class, "getCountries"]);
    Route::get("/", [NationalityController::class, "index"]);
    Route::post("/store", [AdminNationalityController::class, "store"]);
    Route::put("/update", [AdminNationalityController::class, "update"]);
    Route::delete("/destroy", [AdminNationalityController::class, "destroy"]);
});
