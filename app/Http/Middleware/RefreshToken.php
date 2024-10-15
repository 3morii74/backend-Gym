<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Proceed with the request
        if (auth()->check()) {
            try {
                // Refresh the token
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                // Add the new token to the response header
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer ' . $newToken);
                return $response;
            } catch (\Exception $e) {
                return response()->json(['error' => 'Token refresh failed'], 401);
            }
        }
    }
}
