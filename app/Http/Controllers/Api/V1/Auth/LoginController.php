<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt by determining the user type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'SchoolID' => ['required', 'string'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'string'],
        ]);

        // Look up user by SchoolID and ensure they have the 'student' role
        $user = User::where('SchoolID', $request->SchoolID)
            ->whereHas('roles', function ($q) {
                $q->where('RoleName', 'student');
            })
            ->first();

        // Build expected birth date from request
        $loginBirthDate = Carbon::createFromDate(
            $request->birth_year,
            $request->birth_month,
            $request->birth_day
        )->startOfDay();

        if (! $user || ! Hash::check($request->password, $user->PasswordHash)) {
            throw ValidationException::withMessages([
                'SchoolID' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Compare stored birth date with provided date (BirthDate is cast to date on the model)
        if (! $user->BirthDate || ! $user->BirthDate->isSameDay($loginBirthDate)) {
            throw ValidationException::withMessages([
                'SchoolID' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->authenticated($request, $user);
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, User $user)
    {
        $user->tokens()->delete();

        $deviceName = $request->input('device_name', 'auth-token');
        $token = $user->createToken($deviceName)->plainTextToken;
        $user->load('roles');

        // F-018: Add redirection logic for students
        $redirectPath = $this->getStudentRedirectPath($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'redirect_to' => $redirectPath,
        ]);
    }

    /**
     * Get redirection path for student based on group course (F-018).
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function getStudentRedirectPath(User $user): string
    {
        // Only apply redirection logic to students
        if (!$user->hasRole('Student')) {
            return '/dashboard';
        }

        // Find student's group membership
        $groupMembership = DB::table('GroupMembers')
            ->where('StudentUserID', $user->UserID)
            ->first();

        if (!$groupMembership) {
            // Student has no group - redirect to generic landing page
            return '/dashboard';
        }

        $groupId = $groupMembership->GroupID;

        // Get course assignment for the group
        $enrollment = DB::table('Enrollments')
            ->join('Courses', 'Enrollments.CourseID', '=', 'Courses.CourseID')
            ->where('Enrollments.GroupID', $groupId)
            ->select('Courses.CourseID', 'Courses.CourseName')
            ->first();

        if (!$enrollment) {
            // Group has no course assigned - redirect to generic landing page
            return '/dashboard';
        }

        // Redirect based on course
        switch ($enrollment->CourseID) {
            case 'C1': // Methods of Research
                return '/dashboard/mor';
            case 'C2': // Design Project 1
                return '/dashboard/dp1';
            case 'C3': // Design Project 2
                return '/dashboard/dp2';
            default:
                // Unknown course - redirect to generic landing page
                return '/dashboard';
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
