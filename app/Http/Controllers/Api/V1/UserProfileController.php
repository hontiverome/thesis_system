<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('roles');

        $profileData = [
            'full_name' => $user->full_name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
        ];

        if ($user->hasRole('student')) {
            $profileData['student_id'] = $user->id_number;
        }

        return response()->json($profileData);
    }
}
