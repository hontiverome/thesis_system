<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FacultyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CreateFacultyController extends Controller
{
    /**
     * Create a new faculty user with assigned role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFacultyUser(Request $request)
    {
        // Check if authenticated user has admin role
        if (!$request->user() || !$request->user()->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Administrator access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:100', 'unique:Users,Email'],
            'full_name' => ['required', 'string', 'max:100'],
            'faculty_type' => ['required', 'string', 'in:Full-Time,Part-Time'],
            'role_id' => ['required', 'integer', 'exists:Roles,RoleID'],
            'password' => ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',],
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email is already registered.',
            'full_name.required' => 'The full name field is required.',
            'faculty_type.required' => 'The faculty type field is required.',
            'faculty_type.in' => 'The faculty type must be either Full-Time or Part-Time.',
            'role_id.required' => 'The role field is required.',
            'role_id.exists' => 'The selected role does not exist.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        try {
            $user = DB::transaction(function () use ($request) {
                // Use provided password or generate default
                $password = $request->password ?? 'ChangeMe123!';
                
                $user = User::create([
                    'SchoolID' => 'FAC-' . str_pad(User::max('UserID') + 1, 6, '0', STR_PAD_LEFT),
                    'FullName' => $request->full_name,
                    'Email' => $request->email,
                    'PasswordHash' => Hash::make($password),
                ]);

                // Assign role
                $user->roles()->attach($request->role_id);

                // Create faculty details if role is Faculty or higher
                if (in_array($request->role_id, [3, 4, 5])) { // Adviser, Faculty, Research Coordinator
                    FacultyDetail::create([
                        'FacultyDetailID' => 'F' . str_pad($user->UserID, 5, '0', STR_PAD_LEFT),
                        'UserID' => $user->UserID,
                        'FacultyType' => $request->faculty_type,
                    ]);
                }

                return $user;
            });

            return response()->json([
                'message' => 'Faculty user created successfully.',
                'data' => [
                    'user_id' => $user->UserID,
                    'school_id' => $user->SchoolID,
                    'full_name' => $user->FullName,
                    'email' => $user->Email,
                    'faculty_type' => $user->facultyDetail->FacultyType ?? null,
                    'role' => $user->roles->first()->RoleName,
                    'default_password' => $request->password ?? 'ChangeMe123!',
                    'note' => 'User should change password on first login'
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Faculty user creation failed.',
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
