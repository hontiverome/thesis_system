<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

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
        if ($request->filled('student_number')) {
            return $this->loginStudent($request);
        }

        if ($request->filled('faculty_id')) {
            return $this->loginFaculty($request);
        }

        throw ValidationException::withMessages([
            'login_type' => ['Either student_number or faculty_id must be provided.'],
        ]);
    }

    /**
     * Handle a student login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loginStudent(Request $request)
    {
        $request->validate([
            'student_number' => ['required', 'string'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string'],
        ]);
        
        // Combine and validate the birth date
        try {
            $birthDate = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->startOfDay();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'birth_day' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('id_number', $request->student_number)->first();

        // Verify the user, their birth date, and password
        if (!$user || !$user->birth_date || !$user->birth_date->isSameDay($birthDate) || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'student_number' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->authenticated($request, $user);
    }

    /**
     * Handle a faculty login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loginFaculty(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string'],
        ]);

        $user = User::where('id_number', $request->faculty_id)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'faculty_id' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        // Ensure the user has the 'Faculty' role
        if (!$user->roles()->where('name', 'Faculty')->exists()) {
            throw ValidationException::withMessages([
                'faculty_id' => ['The provided credentials are incorrect.'],
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
        $token = $user->createToken($request->device_name)->plainTextToken;
        $user->load('roles');

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
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