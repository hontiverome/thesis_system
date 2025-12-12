<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Invalid or expired token. Please login again.'
            ], 401);
        }

        // Check if user has Administrator role
        if (!$request->user()->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Administrator access required.'
            ], 403);
        }

        return $next($request);
    }
}
