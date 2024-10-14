<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Notifications\SendEmailOtpNotification;

// API Routes

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    // Protected routes for verified users
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
});
Route::post('email/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('auth:api');
Route::post('email/resend-otp', function (Request $request) {
    $user = $request->user();
    $otp = $user->generateOtp(); // Generate a new OTP
    $user->notify(new SendEmailOtpNotification($otp)); // Resend OTP

    return response()->json(['message' => 'Verification OTP resent.'], 200);
})->middleware('auth:api');
