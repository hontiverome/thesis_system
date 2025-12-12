<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupPageController extends Controller
{
    /**
     * Get student's group page (F-015 for students).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyGroup(Request $request)
    {
        $user = $request->user();

        // Check if user is a student
        if (!$user->hasRole('Student')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'This endpoint is for students only.'
            ], 403);
        }

        // Find student's group membership
        $membership = DB::table('GroupMembers')
            ->where('StudentUserID', $user->UserID)
            ->first();

        if (!$membership) {
            return response()->json([
                'message' => 'No group found.',
                'error' => 'You are not yet assigned to a group.'
            ], 404);
        }

        $groupId = $membership->GroupID;

        // Get group details
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json([
                'message' => 'Group not found.',
                'error' => 'Your group could not be found.'
            ], 404);
        }

        // Get all members of the group
        $members = DB::table('GroupMembers')
            ->join('Users', 'GroupMembers.StudentUserID', '=', 'Users.UserID')
            ->where('GroupMembers.GroupID', $groupId)
            ->select('GroupMembers.StudentUserID', 'Users.FullName', 'Users.SchoolID', 'GroupMembers.GroupRole')
            ->orderBy('GroupMembers.GroupRole', 'desc') // GroupLeader first
            ->get();

        // Get adviser details
        $adviser = DB::table('GroupAdvisers')
            ->join('Users', 'GroupAdvisers.AdviserUserID', '=', 'Users.UserID')
            ->where('GroupAdvisers.GroupID', $groupId)
            ->select('Users.UserID', 'Users.FullName', 'Users.Email')
            ->first();

        return response()->json([
            'message' => 'Group page retrieved successfully.',
            'data' => [
                'GroupID' => $group->GroupID,
                'GroupCode' => $group->GroupCode,
                'YearLevel' => $group->YearLevel,
                'Members' => $members,
                'Adviser' => $adviser,
                'CurrentUserRole' => $membership->GroupRole,
                'IsCurrentUserLeader' => $membership->GroupRole === 'GroupLeader'
            ]
        ], 200);
    }

    /**
     * Get specific group page for advisers (F-015 for advisers).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupPage(Request $request, $groupId)
    {
        $user = $request->user();

        // Check if user is an adviser or super admin
        if (!$user->hasRole('Adviser') && !$user->hasRole('Super Admin')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'This endpoint is for advisers only.'
            ], 403);
        }

        // Get group details
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json([
                'message' => 'Group not found.',
                'error' => 'The specified group does not exist.'
            ], 404);
        }

        // Check if group belongs to adviser (unless super admin)
        if (!$user->hasRole('Super Admin') && $group->AdviserUserID !== $user->UserID) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'You can only view your own groups.'
            ], 403);
        }

        // Get all members of the group
        $members = DB::table('GroupMembers')
            ->join('Users', 'GroupMembers.StudentUserID', '=', 'Users.UserID')
            ->where('GroupMembers.GroupID', $groupId)
            ->select('GroupMembers.StudentUserID', 'Users.FullName', 'Users.SchoolID', 'Users.Email', 'GroupMembers.GroupRole')
            ->orderBy('GroupMembers.GroupRole', 'desc') // GroupLeader first
            ->get();

        // Get adviser details
        $adviser = DB::table('GroupAdvisers')
            ->join('Users', 'GroupAdvisers.AdviserUserID', '=', 'Users.UserID')
            ->where('GroupAdvisers.GroupID', $groupId)
            ->select('Users.UserID', 'Users.FullName', 'Users.Email')
            ->first();

        return response()->json([
            'message' => 'Group page retrieved successfully.',
            'data' => [
                'GroupID' => $group->GroupID,
                'GroupCode' => $group->GroupCode,
                'YearLevel' => $group->YearLevel,
                'Members' => $members,
                'Adviser' => $adviser,
                'AdviserUserID' => $group->AdviserUserID
            ]
        ], 200);
    }
}
