<?php

use Illuminate\Support\Facades\Route;
use Modules\Exercise\Http\Controllers\admin\AdminDefaultExerciseController;
use Modules\Exercise\Http\Controllers\admin\AdminMuscleCategoryController;
use Modules\Exercise\Http\Controllers\admin\AdminMusclesController;
use Modules\Exercise\Http\Controllers\user\CustomizedExerciseController;
use Modules\Exercise\Http\Controllers\user\DefaultExerciseController;
use Modules\Exercise\Http\Controllers\user\MuscleCategoryController;
use Modules\Exercise\Http\Controllers\user\MusclesController;

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
    Route::get("/all", [MuscleCategoryController::class, "indexCategoryWithMuscles"]);
    Route::post("/store", [AdminMuscleCategoryController::class, "store"]);
    Route::put("/update", [AdminMuscleCategoryController::class, "update"]);
    Route::delete("/destroy", [AdminMuscleCategoryController::class, "destroy"]);
    Route::get('/muscles', [MuscleCategoryController::class, 'getMusclesByCategory']);
});

Route::group(['prefix' => 'muscle'], function () {
    Route::get("/search", [MusclesController::class, "search"]);
    Route::get("/", [MusclesController::class, "index"]);
    Route::get("/allDefaultExercises", [MusclesController::class, "musclesWithExercise"]);
    Route::get("/allCustomizedExercises", [MusclesController::class, "musclesWithCustomizedExercise"]);
    Route::post("/store", [AdminMusclesController::class, "store"]);
    Route::put("/update", [AdminMusclesController::class, "update"]);
    Route::delete("/destroy", [AdminMusclesController::class, "destroy"]);
});

Route::group(['prefix' => 'defaultExercise'], function () {
    Route::get("/search", [DefaultExerciseController::class, "search"]);
    Route::get("/", [DefaultExerciseController::class, "index"]);
    Route::post("/store", [AdminDefaultExerciseController::class, "store"]);
    Route::put("/update", [AdminDefaultExerciseController::class, "update"]);
    Route::delete("/destroy", [AdminDefaultExerciseController::class, "destroy"]);
});
Route::group(['prefix' => 'customizedExercise'], function () {
    Route::get("/search", [CustomizedExerciseController::class, "search"]);
    Route::get("/", [CustomizedExerciseController::class, "index"]);
    Route::post("/store", [CustomizedExerciseController::class, "store"]);
    Route::put("/update", [CustomizedExerciseController::class, "update"]);
    Route::delete("/destroy", [CustomizedExerciseController::class, "destroy"]);
});
