<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
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
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return $this->apiResponse(
                ['message' => 'Unauthenticated.'],
                401,
                "Unauthorized"
            );
        }

        // Check if the required fields are null
        if (is_null($user->birth_date) || is_null($user->phone) || is_null($user->gender)) {
            return $this->apiResponse(
                ['details' => 'Birth date, phone number, and gender must be provided.'],
                422,
                "Validation Error"
            );
        }

        return $next($request);
    }
}
