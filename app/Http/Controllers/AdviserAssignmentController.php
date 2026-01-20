<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupAdviser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserAssignmentController extends Controller
{
    /**
     * GET /api/v1/groups/{groupId}/adviser
     * Get current adviser assignment for a group
     */
    public function show($groupId)
    {
        $group = Group::with(['advisers.adviser'])->findOrFail($groupId);
        
        $advisers = $group->advisers->map(function($assignment) {
            return [
                'UserID' => $assignment->adviser->UserID,
                'FullName' => $assignment->adviser->FullName,
                'SchoolID' => $assignment->adviser->SchoolID,
                'Email' => $assignment->adviser->Email,
                'assigned_at' => $assignment->created_at,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'GroupID' => $group->GroupID,
                'GroupName' => $group->GroupName,
                'advisers' => $advisers
            ]
        ]);
    }
    
    /**
     * POST /api/v1/groups/{groupId}/adviser
     * Assign adviser to a group (Coordinator/Chairperson only)
     */
    public function assign(Request $request, $groupId)
    {
        $user = $request->user();
        
        // Check if user is coordinator or chairperson
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $isAuthorized = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only coordinators and chairpersons can assign advisers.'
            ], 403);
        }
        
        $validated = $request->validate([
            'adviser_id' => 'required|integer|exists:Users,UserID',
        ]);
        
        // Verify adviser has faculty role
        $adviser = User::with('roles')->findOrFail($validated['adviser_id']);
        $adviserRoles = $adviser->roles()->pluck('RoleName')->toArray();
        
        if (!in_array('faculty', $adviserRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not a faculty member.'
            ], 400);
        }
        
        $group = Group::findOrFail($groupId);
        
        DB::beginTransaction();
        try {
            // Check if adviser is already assigned
            $existing = GroupAdviser::where('GroupID', $groupId)
                ->where('AdviserUserID', $validated['adviser_id'])
                ->first();
            
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'This adviser is already assigned to this group.'
                ], 400);
            }
            
            // Create assignment
            GroupAdviser::create([
                'GroupID' => $groupId,
                'AdviserUserID' => $validated['adviser_id'],
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Adviser assigned successfully',
                'data' => [
                    'GroupID' => $groupId,
                    'GroupName' => $group->GroupName,
                    'adviser' => [
                        'UserID' => $adviser->UserID,
                        'FullName' => $adviser->FullName,
                        'SchoolID' => $adviser->SchoolID,
                        'Email' => $adviser->Email,
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign adviser',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * DELETE /api/v1/groups/{groupId}/adviser/{adviserId}
     * Remove adviser from a group (Coordinator/Chairperson only)
     */
    public function remove(Request $request, $groupId, $adviserId)
    {
        $user = $request->user();
        
        // Check if user is coordinator or chairperson
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $isAuthorized = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only coordinators and chairpersons can remove advisers.'
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            $deleted = GroupAdviser::where('GroupID', $groupId)
                ->where('AdviserUserID', $adviserId)
                ->delete();
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adviser assignment not found.'
                ], 404);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Adviser removed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove adviser',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * GET /api/v1/faculty/available
     * Get list of available faculty members for assignment
     */
    public function getAvailableFaculty(Request $request)
    {
        $user = $request->user();
        
        // Check if user is coordinator or chairperson
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $isAuthorized = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }
        
        // Get all users with faculty role
        $faculty = User::whereHas('roles', function($query) {
            $query->where('RoleName', 'faculty');
        })->get()->map(function($user) {
            return [
                'UserID' => $user->UserID,
                'FullName' => $user->FullName,
                'SchoolID' => $user->SchoolID,
                'Email' => $user->Email,
                'assigned_groups_count' => $user->advisedGroups()->count(),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $faculty
        ]);
    }
}
