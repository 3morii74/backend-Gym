<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\User\Models\User;
use Modules\User\Notifications\SendEmailOtpNotification;
use Modules\User\Notifications\SendPasswordResetOtpNotification;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:users,slug',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20|unique:users,phone',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, 400, $validator->errors());
        }
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'first_name' => ucfirst(strtolower($request->get('first_name'))), // Capitalize first letter
                'last_name' => ucfirst(strtolower($request->get('last_name'))),   // Capitalize first letter
                'slug' => $request->get('slug'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'phone' => $request->get('phone'),
                'birth_date' => $request->get('birth_date'),
                'gender' => $request->get('gender'),
                'status' => $request->get('status'),
            ]);
            $user->generateOtp();
            $user->sendEmailVerificationNotification();
            return $user;
        });
        $token = JWTAuth::fromUser($user);
        return $this->apiResponse(['user' => $user, 'token' => $token], 201, "success");
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate and get the token
        if (! $token = auth()->attempt($credentials)) {
            return $this->apiResponse(null, 401, "Unauthorized");
        }

        // Check if the user wants to stay logged in
        if ($request->has('stay_logged_in') && $request->stay_logged_in) {
            $expirationTime = 20160; // 14 days in minutes (14 * 24 * 60)
            auth()->factory()->setTTL($expirationTime);
        } else {
            $expirationTime = 60; // Set a shorter expiration time (e.g., 1 hour)
            auth()->factory()->setTTL($expirationTime);
        }

        return $this->respondWithToken($token);
    }
    public function logout()
    {
        auth()->logout();
        return $this->apiResponse(null, 401, "Successfully logged out");
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return $this->apiResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 201, "success");
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6', // Validate that the OTP is a 6-digit number
        ]);

        $user = $request->user(); // Get the authenticated user
        if ($user->hasValidOtp($request->otp)) {
            $user->email_verified_at = now(); // Mark email as verified
            $user->email_otp = null; // Clear OTP
            $user->email_otp_expires_at = null; // Clear OTP expiry time
            $user->save();

            return response()->json(['message' => 'Email verified successfully.'], 200);
        }

        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No user found with this email address'], 404);
        }

        // Generate and send OTP
        $user->generatePasswordResetOtp();
        $user->notify(new SendPasswordResetOtpNotification($user->password_reset_otp)); // Assuming you created a notification for this

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    // Reset password using OTP
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->hasValidPasswordResetOtp($request->otp)) {
            return response()->json(['message' => 'Invalid OTP or OTP has expired.'], 400);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->password_reset_otp = null; // Clear OTP
        $user->password_reset_otp_expires_at = null; // Clear OTP expiry
        $user->save();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }
}
