<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // Assuming you have a Role model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // For this registration, we assume the role is always 'Student'.
        $defaultRoleName = 'Student';

        $validator = Validator::make($request->all(), [
            'student_number' => ['required', 'string', 'max:50', 'unique:users,id_number'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the role by name.
        $role = Role::where('name', $defaultRoleName)->firstOrFail();

        $email = $request->student_number . '@iskolarngbayan.pup.edu.ph';

        $user = User::create([
            'first_name' => 'New',
            'last_name' => 'User',
            'id_number' => $request->student_number,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($role->id);

        $token = $user->createToken('auth-token')->plainTextToken;

        // Eager load the roles for the response
        $user->load('roles');

        return response()->json([
            'message' => 'Registration successful.',
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}