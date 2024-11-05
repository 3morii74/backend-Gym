<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {


        return Socialite::driver('google')
            // ->scopes(['openid', 'profile', 'email', 'https://www.googleapis.com/auth/user.phonenumbers.read', 'https://www.googleapis.com/auth/user.birthday.read', 'https://www.googleapis.com/auth/user.gender.read	'])->stateless()
            ->stateless()
            ->with(['prompt' => 'select_account'])  // This forces the user to select an account every time
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            // Retrieve the Google user details
            $googleUser = Socialite::driver('google')->stateless()->user();
            // Check if the user already exists in the database
            $user = User::where('email', $googleUser->email)->first();
            // dd($user);
            if (!$user) {
                // Create the user in a transaction
                $user = DB::transaction(function () use ($googleUser) {
                    return User::create([
                        'first_name' => ucfirst(strtolower($googleUser->user['given_name'] ?? '')), // Google API has given_name
                        'last_name' => ucfirst(strtolower($googleUser->user['family_name'] ?? '')),  // Google API has family_name
                        'slug' => Str::slug($googleUser->name),
                        'email' => $googleUser->email,
                        'password' => Hash::make(Str::random(16)), // Generate a random password since it won't be used
                        'phone' => null,
                        'birth_date' =>  null,
                        'gender' =>  null,
                        'status' => 'active',
                        'email_verified_at' => now(),
                        'social_id' => $googleUser->id,
                        'social_type' => 'google',
                    ]);
                });
            } else {
                $user->update([
                    'social_id' => $googleUser->id,
                    'social_type' => 'google',
                ]);
            }
            // Generate JWT token for the user
            $token = JWTAuth::fromUser($user);

            // Create a temporary authorization code
            $authCode = Str::random(40);

            // Store the token in cache with a 3-minute expiration
            Cache::put('auth_code_' . $authCode, [$token, $user], now()->addMinutes(5));

            // Redirect URL to the frontend
            $frontendUrl = env('FRONTEND_URL'); // Make sure FRONTEND_URL is set in .env file
            $redirectUrl = $frontendUrl . '/auth/callback?code=' . $authCode;
            return redirect()->to($redirectUrl);
        } catch (ValidationException $e) {
            // Handle errors gracefully
            return redirect('/error')->with('e', $e);
        }
    }

    public function exchangeAuthCode(Request $request)
    {
        
        try {
            $authCode = $request->input('code');
            $cachedData = Cache::get('auth_code_' . $authCode);
            
            if ($cachedData) {
                // dd("asd");
                [$token, $account] = $cachedData;
                Cache::forget('auth_code_' . $authCode);
                return $this->respondWithToken($token, $account);
            } else {
                // If the cached data is not found, throw a ValidationException
                throw new ValidationException('Auth code expired or not found', 404);
            }
        } catch (ValidationException $e) {
            // Handle the validation exception
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            // Handle any other exceptions that may occur
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }


    protected function respondWithToken($token, $account)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'account' => $account
        ];
    }
}
