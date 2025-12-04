<?php

namespace App\Http\Controllers\Auth;

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
            'student_number' => ['required', 'string', 'max:50', 'unique:users,id_number'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1,', 'max:31'],
            'birth_year' => ['required', 'integer', 'digits:4'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        // Combine birth date parts and validate as a single date
        try {
            $birthDate = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->startOfDay();
        } catch (\Exception $e) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => ['birth_day' => ['The provided birth date is not a valid date.']]], 422);
        }

        $email = $request->student_number . '@iskolarngbayan.pup.edu.ph';
        
        // Find the 'Student' role, or fail.
        $studentRole = Role::where('name', 'Student')->first();
        if (!$studentRole) {
            return response()->json([
                'message' => 'Registration service is currently unavailable.',
                'errors' => ['role' => ['The default registration role could not be found. Please contact support.']],
            ], 500);
        }

        $user = DB::transaction(function () use ($request, $birthDate, $email, $studentRole) {
            $user = User::create([
                'first_name' => null,
                'last_name' => null,
                'id_number' => $request->student_number,
                'birth_date' => $birthDate,
                'email' => $email,
                'password' => Hash::make($request->password),
            ]);
    
            $user->roles()->attach($studentRole->id);

            return $user;
        });

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