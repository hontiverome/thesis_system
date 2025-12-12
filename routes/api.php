<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\{LoginController, RegisterController, FacultyLoginController, PasswordController};
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Middleware\SudentVerification;
use App\Services\StudentProposalService;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\V1\CreateFacultyController;
use App\Http\Controllers\Api\V1\AdminListController;
use App\Http\Controllers\Api\V1\AdminRoleController;
use App\Http\Controllers\Api\V1\GroupPageController;
use App\Http\Controllers\Api\V1\AdminGroupController;
use App\Http\Controllers\Api\V1\AdviserGroupController;
use App\Http\Controllers\Api\V1\StudentController;

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


// Student-only routes
Route::prefix('v1/groups')->middleware(['auth:sanctum', 'student.verify'])->group(function () {
     Route::post('/{groupId}/proposals', [StudentController::class, 'submitProposal']);
     Route::get('/{groupId}/proposals', [StudentController::class, 'getProposalStatus']);
     Route::get('/proposals/student/group', [StudentController::class, 'displayInfo']);
     Route::post('/{groupId}/manuscript', [StudentController::class, 'submitProposal']);
     Route::get('/{groupId}/approval-status', [StudentController::class, 'getEligibleProposals']);
     Route::get('/faculty', [StudentProposalService::class, 'getFacultyList']);
     Route::post('/{groupdId}/manuscript', [StudentController::class, 'submitManuscript']);
     Route::post('/invitation', [StudentController::class, 'invitation']);
     Route::post('/evaluation', [StudentController::class, 'evaluation']);
     Route::get('/{id}/delete', [StudentController::class, 'delete']);
     Route::post('{groupId}/select-title', [StudentController::class, 'selectTitleForDefense']);
     Route::get('/{groupId}/defense', [StudentController::class, 'getDefenseVerdict']);
});

// Student API Tests
Route::post('/student/test', function (Request $request) {
    
    $user = User::with('groups')->find(101); 
    if (!$user) return response()->json(['error' => 'User 101 not found'], 404);
    
    Sanctum::actingAs($user, ['*']);
    $groupId = $user->groups->first()->GroupID ?? 1; 
    $internalRequest = Request::create(
        "/api/v1/groups/{$groupId}/defense", 'GET',                     
        $request->all()         
    );
    $response = app()->handle($internalRequest);

    return $response;
});

// Admin routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->name('api.admin.')->group(function () {
    Route::post('/users', [CreateFacultyController::class, 'createFacultyUser'])->name('users.create');
    Route::get('/users/list', [AdminListController::class, 'listUsers'])->name('users.list');
    Route::put('/users/{userId}/role', [AdminRoleController::class, 'changeRole'])->name('users.role.change');
    Route::get('/roles/available', [AdminRoleController::class, 'getAvailableRoles'])->name('roles.available');
    
    // F-017: Group Management
    Route::get('/groups', [AdminGroupController::class, 'getGroupsWithCourses'])->name('groups.list');
    Route::put('/groups/{groupId}/course', [AdminGroupController::class, 'assignCourse'])->name('groups.course.assign');
    Route::get('/courses/available', [AdminGroupController::class, 'getAvailableCourses'])->name('courses.available');
});

// Adviser routes
Route::prefix('v1/adviser')->middleware(['auth:sanctum', 'adviser'])->name('api.adviser.')->group(function () {
    // F-012: Create new group
    Route::post('/groups', [AdviserGroupController::class, 'createGroup'])->name('groups.create');
    
    // Group management
    Route::delete('/groups/{groupId}', [AdviserGroupController::class, 'deleteGroup'])->name('groups.delete');
    
    // F-013: Group member management
    Route::post('/groups/{groupId}/members', [AdviserGroupController::class, 'addMember'])->name('groups.members.add');
    Route::delete('/groups/{groupId}/members/{studentUserId}', [AdviserGroupController::class, 'removeMember'])->name('groups.members.remove');
    
    // F-014: Set group leader
    Route::put('/groups/{groupId}/leader', [AdviserGroupController::class, 'setGroupLeader'])->name('groups.leader.set');
    
    // Helper endpoints
    Route::get('/students/available', [AdviserGroupController::class, 'getAvailableStudents'])->name('students.available');
    Route::get('/groups/my', [AdviserGroupController::class, 'getMyGroups'])->name('groups.my');
});

// F-015 Group Page routes
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Student group page
    Route::get('/groups/my-group', [GroupPageController::class, 'getMyGroup'])->name('groups.my-group');
    
    // Adviser group page (requires adviser middleware)
    Route::get('/adviser/groups/{groupId}', [GroupPageController::class, 'getGroupPage'])
        ->middleware(['adviser'])
        ->name('adviser.groups.page');
});
