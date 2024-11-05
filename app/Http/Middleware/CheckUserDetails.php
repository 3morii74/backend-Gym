<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CheckUserDetails
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Assuming the user is authenticated and the data is stored on the user model
        $user = auth()->user();
        // Check if the required fields are null
        if (is_null($user->birth_date) || is_null($user->phone) || is_null($user->gender)) {
            return $this->apiResponse([
                'details' => 'Birth date, phone number, and gender must be provided.',
            ], 422, "Validation Error");
        }
        return $next($request);
    }
}
