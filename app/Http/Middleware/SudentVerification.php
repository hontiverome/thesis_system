<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SudentVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
             return response()->json(['message' => 'Unauthorized. Authentication required.'], 401);
        }

        /** @var \App\Models\User $user */ 
        $user = Auth::user();
        
        if (! $user->hasRole('Student')) {
            return response()->json(['message' => 'Unauthorized. Student access only.'], 403);
        }

        return $next($request);
    }
}