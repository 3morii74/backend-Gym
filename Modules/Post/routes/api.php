<?php

use App\Http\Middleware\EnsureApiAuthenticated;
use App\Http\Middleware\EnsureEmailVerified;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PostController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 * Here is where you can register API routes for your application. These
 *
*/


Route::group(['middleware' => [EnsureApiAuthenticated::class, EnsureEmailVerified::class], 'prefix' => 'post'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/create', [PostController::class, 'createContent']);
});
