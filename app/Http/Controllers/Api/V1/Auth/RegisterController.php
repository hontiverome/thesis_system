<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $validator = Validator::make($request->all(), [
            'SchoolID' => ['required', 'string', 'max:50', 'unique:Users,SchoolID'],
            'FirstName' => ['required', 'string', 'max:100'],
            'MiddleInitial' => ['nullable', 'string', 'max:100'],
            'Surname' => ['required', 'string', 'max:100'],
            'Email' => ['required', 'string', 'email', 'max:100', 'unique:Users,Email'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        try {
            $birthDate = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->startOfDay();
            $fullName = $request->FirstName . ' ' . ($request->MiddleInitial ? $request->MiddleInitial . '. ' : '') . $request->Surname;

            $user = DB::transaction(function () use ($request, $birthDate, $fullName) {
                $user = User::create([
                    'SchoolID' => $request->SchoolID,
                    'FullName' => $fullName,
                    'Email' => $request->Email,
                    'BirthDate' => $birthDate->format('Y-m-d'),
                    'PasswordHash' => $request->password,
                ]);

                // The 'student' role is assigned by default on registration
                $studentRole = Role::where('RoleName', 'student')->first();
                if ($studentRole) {
                    $user->roles()->attach($studentRole);
                }

                return $user;
            });

            $token = $user->createToken('auth-token')->plainTextToken;

            // Eager load the roles for the response
            $user->load('roles');

            return response()->json([
                'message' => 'Registration successful.',
                'user' => [
                    'UserID' => $user->UserID,
                    'FullName' => $user->FullName,
                    'Email' => $user->Email,
                    'roles' => $user->roles->pluck('RoleName'),
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed.',
                'errors' => [
                    'server' => [
                        'An unexpected error occurred.',
                        $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }
}
