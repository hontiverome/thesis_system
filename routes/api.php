<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{LoginController, RegisterController};

// Public routes
Route::post('/v1/login', [LoginController::class, 'login'])
    ->name('api.login');

Route::post('/v1/register', [RegisterController::class, 'register'])
    ->name('api.register');

// Test endpoint to verify CORS is working
Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'API is working!',
        'user' => $request->user() ? $request->user()->only('id', 'name', 'email') : null,
        'auth' => [
            'via' => $request->bearerToken() ? 'token' : 'session',
            'authenticated' => $request->user() ? true : false
        ]
    ]);
});

// Test protected route
Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Authenticated!',
        'user' => $request->user()->only('id', 'name', 'email')
    ]);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->name('api.')->group(function () {
    // Auth routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user()->only('id', 'name', 'email'); // It's good practice to only return what's needed.
    })->name('user');
});
