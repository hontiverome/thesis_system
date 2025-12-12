<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRoleController extends Controller
{
    /**
     * Change a user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRole(Request $request, $userId)
    {
        // Check if authenticated user has admin role
        if (!$request->user() || !$request->user()->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Administrator access required.'
            ], 403);
        }

        // Prevent admin from changing their own role
        if ($request->user()->UserID == $userId) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Cannot change your own role.'
            ], 403);
        }

        $request->validate([
            'role_id' => ['required', 'integer', 'exists:Roles,RoleID'],
        ], [
            'role_id.required' => 'The role field is required.',
            'role_id.exists' => 'The selected role does not exist.',
        ]);

        // Get available roles (exclude Student and Admin)
        $availableRoles = Role::whereNotIn('RoleName', ['Student', 'Administrator'])
            ->pluck('RoleID')
            ->toArray();

        if (!in_array($request->role_id, $availableRoles)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'role_id' => ['The selected role is not allowed for assignment.']
                ]
            ], 422);
        }

        try {
            $user = User::findOrFail($userId);

            DB::transaction(function () use ($user, $request) {
                // Remove all existing roles
                $user->roles()->detach();
                
                // Assign new role
                $user->roles()->attach($request->role_id);
            });

            // Get the new role name for response
            $newRole = Role::find($request->role_id);

            return response()->json([
                'message' => 'User role updated successfully.',
                'data' => [
                    'user_id' => $user->UserID,
                    'full_name' => $user->FullName,
                    'new_role' => $newRole->RoleName,
                    'new_role_id' => $newRole->RoleID,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.',
                'errors' => [
                    'user_id' => ['The specified user does not exist.']
                ]
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Role update failed.',
                'errors' => [
                    'server' => [
                        'An unexpected error occurred.',
                        $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }

    /**
     * Get available roles for assignment (excluding Student and Admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableRoles(Request $request)
    {
        // Check if authenticated user has admin role
        if (!$request->user() || !$request->user()->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Administrator access required.'
            ], 403);
        }

        $roles = Role::whereNotIn('RoleName', ['Student', 'Administrator'])
            ->orderBy('RoleID')
            ->get(['RoleID', 'RoleName']);

        return response()->json([
            'message' => 'Available roles retrieved successfully.',
            'data' => $roles
        ], 200);
    }
}
