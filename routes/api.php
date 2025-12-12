<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\{LoginController, RegisterController, FacultyLoginController, PasswordController};
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Controllers\Api\V1\CreateFacultyController;
use App\Http\Controllers\Api\V1\AdminListController;
use App\Http\Controllers\Api\V1\AdminRoleController;
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
    Route::put('password', [PasswordController::class, 'update'])->name('api.password.update');
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
});

// Admin routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->name('api.admin.')->group(function () {
    Route::post('/users', [CreateFacultyController::class, 'createFacultyUser'])->name('users.create');
    Route::get('/users/list', [AdminListController::class, 'listUsers'])->name('users.list');
    Route::put('/users/{userId}/role', [AdminRoleController::class, 'changeRole'])->name('users.role.change');
    Route::get('/roles/available', [AdminRoleController::class, 'getAvailableRoles'])->name('roles.available');
});

// Adviser routes
Route::prefix('v1/adviser')->middleware(['auth:sanctum', 'adviser'])->name('api.adviser.')->group(function () {
    // F-012: Create new group
    Route::post('/groups', [AdviserGroupController::class, 'createGroup'])->name('groups.create');
    
    // F-013: Group member management
    Route::post('/groups/{groupId}/members', [AdviserGroupController::class, 'addMember'])->name('groups.members.add');
    Route::delete('/groups/{groupId}/members/{studentUserId}', [AdviserGroupController::class, 'removeMember'])->name('groups.members.remove');
    
    // F-014: Set group leader
    Route::put('/groups/{groupId}/leader', [AdviserGroupController::class, 'setGroupLeader'])->name('groups.leader.set');
    
    // Helper endpoints
    Route::get('/students/available', [AdviserGroupController::class, 'getAvailableStudents'])->name('students.available');
    Route::get('/groups/my', [AdviserGroupController::class, 'getMyGroups'])->name('groups.my');
});
