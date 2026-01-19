<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\{LoginController, RegisterController, FacultyLoginController, PasswordController};
use App\Http\Controllers\Api\V1\UserProfileController;
use App\Http\Controllers\Api\V1\DefenseService;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\V1\CreateFacultyController;
use App\Http\Controllers\Api\V1\AdminListController;
use App\Http\Controllers\Api\V1\AdminRoleController;
use App\Http\Controllers\Api\V1\GroupPageController;
use App\Http\Controllers\Api\V1\AdminGroupController;
use App\Http\Controllers\Api\V1\AdviserGroupController;
use App\Http\Controllers\Api\V1\GroupPanelController;
use App\Http\Controllers\Api\V1\AdviserAssignmentController;
use App\Http\Controllers\Api\V1\AdviserCourseController;
use App\Http\Controllers\Api\V1\AdviserProposalController;
use App\Http\Controllers\Api\V1\AdviserPanelController;
use App\Http\Controllers\Api\V1\AdviserDefenseController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\StudentLeaderController;
use App\Models\Defense;
use App\Http\Middleware\GroupLeaderMiddleware;

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
    $user = $request->user() ?? auth('sanctum')->user();
    return response()->json([
        'message' => 'API is working!',
        'user' => $user ? $user->only('id', 'name', 'email') : null,
        'auth' => [
            'via' => $request->bearerToken() ? 'token' : 'session',
            'authenticated' => $user ? true : false
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

// Test endpoint to get a token for the test student (for Postman testing)
Route::get('/test/token/student', function () {
    $user = User::find(113);
    if (!$user) {
        return response()->json(['error' => 'User 113 not found.'], 404);
    }
    $token = $user->createToken('test-token')->plainTextToken;
    return response()->json(['token' => $token, 'message' => 'Use this token in Authorization header: Bearer <token>']);
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
     
    # Group Leader APIs
     Route::post('/{groupId}/proposals', [StudentLeaderController::class, 'submitProposal'])->middleware(GroupLeaderMiddleware::class); # F-104
     Route::get('/{groupId}/approval-status', [StudentLeaderController::class, 'getEligibleProposals'])->middleware(GroupLeaderMiddleware::class); # F-107
     Route::post('/{groupId}/select-title', [StudentLeaderController::class, 'selectTitleForDefense'])->middleware(GroupLeaderMiddleware::class); # F--108
     Route::post('/{groupId}/manuscript', [StudentLeaderController::class, 'submitManuscript'])->middleware(GroupLeaderMiddleware::class); # F-109

     # General APIs
     Route::get('/{groupId}/proposals', [StudentController::class, 'getProposal']); # F-105 
     Route::get('/{groupId}/defense', [StudentController::class, 'getDefenseVerdict']); # F-113

     # Other Student APIs
     Route::get('/group', [StudentController::class, 'displayInfo']); # Display Group Information (Group Number, Adviser, Section)
     Route::get('/faculty', [StudentController::class, 'faculty']); # Display All User with Faculty Role
     Route::get('/invitation', [StudentController::class, 'invitation']); # Display all defense panel invitation status
     Route::get('/evaluation-forms', [StudentController::class, 'defenseEvalutaion']); # Retrieve all evaluation forms
     Route::get('/{groupId}/manuscript', [StudentController::class, 'getManuscript']); # Retrieve Manuscript
     Route::get('/{id}/delete', [StudentLeaderController::class, 'delete'])->middleware(GroupLeaderMiddleware::class);; # Delete Proposal
     
});
     Route::middleware('auth:sanctum')->get('auth/dashboard', [StudentController::class, 'dashboard']); # F-103


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

    // F-016: Course Management
    Route::get('/courses', [AdviserCourseController::class, 'getAdviserCourses']);

    // F-018: Proposal Management
    Route::get('/proposals', [AdviserProposalController::class, 'getProposals']);

    // F-019: Panel Management
    Route::get('/panel/invitations', [AdviserPanelController::class, 'getPanelInvitations']);
    Route::post('/panel/invitations/{defenseId}/respond', [AdviserPanelController::class, 'respondToInvitation']);

    // Panel Evaluation (Judging)
    Route::get('/panel/defenses', [AdviserPanelController::class, 'getDefensesToEvaluate']);
    Route::post('/panel/defenses/{defenseId}/verdict', [AdviserPanelController::class, 'submitVerdict']);

    // F-020: Defense Management
    Route::get('/defenses', [AdviserDefenseController::class, 'getGroupDefenses']); // List
    Route::put('/defenses/{defenseId}/status', [AdviserDefenseController::class, 'updateDefenseStatus']); // Update Status

    // F-021: Document Management
    Route::post('/defenses/{defenseId}/documents', [AdviserDefenseController::class, 'uploadDocument']); // Upload
    Route::get('/defenses/{defenseId}/documents', [AdviserDefenseController::class, 'getDefenseDocuments']); // View Files
    Route::delete('/documents/{fileId}', [AdviserDefenseController::class, 'deleteDocument']); // Delete File
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

// Assign adviser to blocks
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Dropdown for the "Add as Adviser" modal
    Route::get('/blocks/available', [AdviserAssignmentController::class, 'getBlocksForDropdown']);
    
    // The "Save" button action
    Route::post('/blocks/assign-adviser', [AdviserAssignmentController::class, 'assignAdviserToBlock']);
});
