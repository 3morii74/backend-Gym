<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
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
            return response()->json(['error' => 'Email not verified.'], 403);
        }

        return $next($request);
    }
}
