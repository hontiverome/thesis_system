<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GroupAdviser;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdviserModuleController extends Controller
{
    /**
     * Get all groups for the authenticated adviser
     * GET /api/v1/adviser/groups
     */
    public function getAdvisedGroups(Request $request)
    {
        $userId = $request->user()->UserID;
        
        $groups = Group::whereHas('advisers', function($query) use ($userId) {
            $query->where('AdviserID', $userId);
        })
        ->with(['members.user', 'proposal', 'advisers.adviser'])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $groups
        ]);
    }

    /**
     * Get specific group details
     * GET /api/v1/adviser/groups/{group_id}
     */
    public function getGroupDetails($groupId)
    {
        $group = Group::with([
            'members.user',
            'proposal',
            'advisers.adviser',
            'defenses'
        ])->find($groupId);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $group
        ]);
    }

    /**
     * Assign adviser to a group (Admin only)
     * POST /api/v1/groups/{group_id}/advisers
     */
    public function assignAdviser(Request $request, $groupId)
    {
        $validator = Validator::make($request->all(), [
            'adviser_id' => 'required|exists:Users,UserID',
            'role' => 'nullable|in:primary,co-adviser'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $group = Group::find($groupId);
        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found'
            ], 404);
        }

        // Check if adviser already assigned
        $existing = GroupAdviser::where('GroupID', $groupId)
            ->where('AdviserID', $request->adviser_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Adviser already assigned to this group'
            ], 400);
        }

        $assignment = GroupAdviser::create([
            'GroupID' => $groupId,
            'AdviserID' => $request->adviser_id,
            'Role' => $request->role ?? 'primary',
            'AssignedAt' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Adviser assigned successfully',
            'data' => $assignment
        ], 201);
    }

    /**
     * Remove adviser from a group (Admin only)
     * DELETE /api/v1/groups/{group_id}/advisers/{adviser_id}
     */
    public function removeAdviser($groupId, $adviserId)
    {
        $assignment = GroupAdviser::where('GroupID', $groupId)
            ->where('AdviserID', $adviserId)
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Adviser assignment not found'
            ], 404);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Adviser removed from group'
        ]);
    }
}
