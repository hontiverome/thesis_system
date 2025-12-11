<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FacultyLoginController extends Controller
{
    /**
     * Handle a faculty authentication attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'SchoolID' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['sometimes', 'string'],
        ]);

        // Look up user by SchoolID and ensure they have a non-student role
        $user = User::where('SchoolID', $request->SchoolID)
            ->whereHas('roles', function ($q) {
                $q->where('RoleName', '!=', 'student');
            })
            ->first();

        if (! $user || ! Hash::check($request->password, $user->PasswordHash)) {
            throw ValidationException::withMessages([
                'SchoolID' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->authenticated($request, $user);
    }

    /**
     * Send the response after the faculty user was authenticated.
     */
    protected function authenticated(Request $request, User $user)
    {
        $user->tokens()->delete();

        $deviceName = $request->input('device_name', 'auth-token');
        $token = $user->createToken($deviceName)->plainTextToken;
        $user->load('roles');

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
