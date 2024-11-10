<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('api')->user();
        // Check if the user is authenticated and their email is verified
        if ($user && !$user->hasVerifiedEmail()) {
            // Return a JSON response instead of redirecting
            return $this->apiResponse($data = null, $status = 403, $message = 'Email not verified.');
        }

        return $next($request);
    }
}
