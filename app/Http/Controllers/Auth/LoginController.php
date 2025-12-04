<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'student_number' => ['required', 'string'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        
        // Combine and validate the birth date
        try {
            $birthDate = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->startOfDay();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'birth_day' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Find the user by student number
        $user = User::where('id_number', $request->student_number)->first();

        // Verify the user, their birth date, and password
        if (!$user || 
            !$user->birth_date ||
            !$user->birth_date->isSameDay($birthDate) || 
            !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'student_number' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        // Revoke all previous tokens
        $user->tokens()->delete();
        
        // Create new token
        $token = $user->createToken($request->device_name)->plainTextToken;

        // Eager load roles for the response
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
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}