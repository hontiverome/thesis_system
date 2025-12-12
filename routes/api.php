<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\{LoginController, RegisterController, FacultyLoginController};
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Controllers\Api\V1\AdviserCourseController;
use App\Http\Controllers\Api\V1\AdviserGroupController;

// Public routes
Route::prefix('v1/auth')->group(function () {
    // Student login
    Route::post('login/student', [LoginController::class, 'login'])
        ->name('api.login.student');

    // Faculty login
    Route::post('login/faculty', [FacultyLoginController::class, 'login'])
        ->name('api.login.faculty');

    Route::post('register', [RegisterController::class, 'register'])
        ->name('api.register');
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->name('api.password.email');
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('password.reset');
});

// Protected Auth routes
Route::prefix('v1/auth')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('api.logout');
});

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
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user()->only('id', 'name', 'email'); // It's good practice to only return what's needed.
    })->name('user');
    
    Route::get('/users/me', [UserProfileController::class, 'show'])->name('users.me');
    Route::get('/adviser/courses', [AdviserCourseController::class, 'getAdviserCourses']);
    Route::get('/adviser/groups', [AdviserGroupController::class, 'getAdviserGroups']);
});


