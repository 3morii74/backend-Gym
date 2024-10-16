<?php

use Illuminate\Support\Facades\Route;
use Modules\Exercise\Http\Controllers\admin\AdminMuscleCategoryController;
use Modules\Exercise\Http\Controllers\ExerciseController;
use Modules\Exercise\Http\Controllers\user\MuscleCategoryController;  

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::group(['prefix' => 'muscleCategory'], function () {
    Route::get("/search", [MuscleCategoryController::class, "search"]);
    Route::get("/", [MuscleCategoryController::class, "index"]);
    Route::post("/store", [AdminMuscleCategoryController::class, "store"]);
    Route::put("/update", [AdminMuscleCategoryController::class, "update"]);
    Route::delete("/destroy", [AdminMuscleCategoryController::class, "destroy"]);
});