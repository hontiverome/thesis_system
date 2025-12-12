<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupAdviser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdviserGroupController extends Controller
{
    /**
     * Create a new group assigned to the adviser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'GroupCode' => ['required', 'string', 'max:20', 'unique:Groups,GroupCode'],
            'YearLevel' => ['required', 'integer', 'min:1', 'max:6'],
        ], [
            'GroupCode.required' => 'The group code field is required.',
            'GroupCode.unique' => 'The group code is already taken.',
            'YearLevel.required' => 'The year level field is required.',
        ]);

        try {
            $group = DB::transaction(function () use ($request) {
                // Generate unique GroupID
                $lastGroup = Group::orderBy('GroupID', 'desc')->first();
                $lastId = $lastGroup ? (int)str_replace('G', '', $lastGroup->GroupID) : 0;
                $newId = $lastId + 1;
                
                $group = Group::create([
                    'GroupID' => 'G' . str_pad($newId, 3, '0', STR_PAD_LEFT),
                    'GroupCode' => $request->GroupCode,
                    'YearLevel' => $request->YearLevel,
                    'AdviserUserID' => $request->user()->UserID,
                ]);

                // Auto-assign adviser to the group
                GroupAdviser::create([
                    'GroupID' => $group->GroupID,
                    'AdviserUserID' => $request->user()->UserID,
                ]);

                return $group;
            });

            return response()->json([
                'message' => 'Group created successfully.',
                'data' => [
                    'GroupID' => $group->GroupID,
                    'GroupCode' => $group->GroupCode,
                    'YearLevel' => $group->YearLevel,
                    'AdviserUserID' => $group->AdviserUserID,
                    'AdviserName' => $request->user()->FullName,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Group creation failed.',
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
     * Add a member to the group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember(Request $request, $groupId)
    {
        $request->validate([
            'StudentUserID' => ['required', 'integer', 'exists:Users,UserID'],
            'GroupRole' => ['nullable', 'string', 'in:Member,GroupLeader'],
        ], [
            'StudentUserID.required' => 'The student ID field is required.',
            'StudentUserID.exists' => 'The selected student does not exist.',
            'GroupRole.in' => 'The group role must be either Member or GroupLeader.',
        ]);

        try {
            $student = User::findOrFail($request->StudentUserID);

            // Check if student has Student role
            if (!$student->hasRole('Student')) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'StudentUserID' => ['Only students can be added to groups.']
                    ]
                ], 422);
            }

            // Check if student is already in another group
            $existingMembership = GroupMember::where('StudentUserID', $request->StudentUserID)->first();
            if ($existingMembership) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'StudentUserID' => ['Student is already in another group.']
                    ]
                ], 422);
            }

            // Check if student is already in this group
            $group = Group::findOrFail($groupId);
            $existingInGroup = $group->members()->where('StudentUserID', $request->StudentUserID)->exists();
            if ($existingInGroup) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'StudentUserID' => ['Student is already in this group.']
                    ]
                ], 422);
            }

            // Check if group already has a leader when trying to add a GroupLeader
            if ($request->GroupRole === 'GroupLeader') {
                $existingLeader = DB::table('GroupMembers')
                    ->where('GroupID', $groupId)
                    ->where('GroupRole', 'GroupLeader')
                    ->exists();
                
                if ($existingLeader) {
                    return response()->json([
                        'message' => 'Validation failed.',
                        'errors' => [
                            'GroupRole' => ['Group already has a leader.']
                        ]
                    ], 422);
                }
            }

            // Add member to group
            GroupMember::create([
                'GroupID' => $groupId,
                'StudentUserID' => $request->StudentUserID,
                'GroupRole' => $request->GroupRole ?? 'Member',
            ]);

            return response()->json([
                'message' => 'Member added successfully.',
                'data' => [
                    'GroupID' => $groupId,
                    'StudentUserID' => $request->StudentUserID,
                    'StudentName' => $student->FullName,
                    'GroupRole' => $request->GroupRole ?? 'Member',
                ]
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group or student not found.',
                'errors' => [
                    'resource' => ['The specified resource does not exist.']
                ]
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add member.',
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
     * Remove a member from the group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @param  int  $studentUserId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMember(Request $request, $groupId, $studentUserId)
    {
        try {
            $membership = GroupMember::where('GroupID', $groupId)
                ->where('StudentUserID', $studentUserId)
                ->firstOrFail();

            $membership->delete();

            return response()->json([
                'message' => 'Member removed successfully.',
                'data' => [
                    'GroupID' => $groupId,
                    'StudentUserID' => $studentUserId,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Membership not found.',
                'errors' => [
                    'membership' => ['The specified member is not in this group.']
                ]
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove member.',
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
     * Set a member as group leader.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setGroupLeader(Request $request, $groupId)
    {
        $request->validate([
            'StudentUserID' => ['required', 'integer', 'exists:Users,UserID'],
        ], [
            'StudentUserID.required' => 'The student ID field is required.',
            'StudentUserID.exists' => 'The selected student does not exist.',
        ]);

        try {
            $group = Group::findOrFail($groupId);
            $student = User::findOrFail($request->StudentUserID);

            // Check if student is a member of this group
            $membership = GroupMember::where('GroupID', $groupId)
                ->where('StudentUserID', $request->StudentUserID)
                ->firstOrFail();

            DB::transaction(function () use ($groupId, $request) {
                // Find current leader in this group
                $currentLeaderMembership = GroupMember::where('GroupID', $groupId)
                    ->where('GroupRole', 'GroupLeader')
                    ->first();

                // Remove GroupLeader role from current leader if exists
                if ($currentLeaderMembership) {
                    $currentLeaderUser = User::find($currentLeaderMembership->StudentUserID);
                    if ($currentLeaderUser && $currentLeaderUser->hasRole('GroupLeader')) {
                        // Check if user is only a GroupLeader in this group
                        $otherLeaderships = GroupMember::where('StudentUserID', $currentLeaderMembership->StudentUserID)
                            ->where('GroupID', '!=', $groupId)
                            ->where('GroupRole', 'GroupLeader')
                            ->count();
                        
                        // Only remove GroupLeader role if not leading other groups
                        if ($otherLeaderships === 0) {
                            $currentLeaderUser->roles()->detach(2); // RoleID 2 = GroupLeader
                        }
                    }
                }

                // Remove leader status from all members in this group
                GroupMember::where('GroupID', $groupId)
                    ->update(['GroupRole' => 'Member']);

                // Set new leader
                GroupMember::where('GroupID', $groupId)
                    ->where('StudentUserID', $request->StudentUserID)
                    ->update(['GroupRole' => 'GroupLeader']);

                // Add GroupLeader role to new leader
                $newLeaderUser = User::find($request->StudentUserID);
                if ($newLeaderUser && !$newLeaderUser->hasRole('GroupLeader')) {
                    $newLeaderUser->roles()->attach(2); // RoleID 2 = GroupLeader
                }
            });

            return response()->json([
                'message' => 'Group leader updated successfully.',
                'data' => [
                    'GroupID' => $groupId,
                    'GroupCode' => $group->GroupCode,
                    'NewLeaderUserID' => $request->StudentUserID,
                    'NewLeaderName' => $student->FullName,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group, student, or membership not found.',
                'errors' => [
                    'resource' => ['The specified resource does not exist or student is not in this group.']
                ]
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update group leader.',
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
     * Get all available students (not in any group).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableStudents(Request $request)
    {
        $students = User::whereHas('roles', function ($query) {
            $query->where('RoleName', 'Student');
        })
        ->whereDoesntHave('groups')
        ->select(['UserID', 'SchoolID', 'FullName', 'Email'])
        ->get();

        return response()->json([
            'message' => 'Available students retrieved successfully.',
            'data' => $students
        ], 200);
    }

    /**
     * Delete a group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGroup(Request $request, $groupId)
    {
        try {
            $group = Group::findOrFail($groupId);

            // Check if group belongs to adviser
            if ($group->AdviserUserID !== $request->user()->UserID) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => 'You can only delete your own groups.'
                ], 403);
            }

            // Check if group has members
            $memberCount = DB::table('GroupMembers')
                ->where('GroupID', $groupId)
                ->count();

            if ($memberCount > 0) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'GroupID' => ['Cannot delete group with members. Remove all members first.']
                    ]
                ], 422);
            }

            // Check if Group has a proposal (through Enrollments)
            $hasProposal = DB::table('Proposals')
                ->join('Enrollments', 'Proposals.EnrollmentID', '=', 'Enrollments.EnrollmentID')
                ->where('Enrollments.GroupID', $groupId)
                ->exists();

            if ($hasProposal) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'GroupID' => ['Cannot delete Group with existing proposals.']
                    ]
                ], 422);
            }

            DB::transaction(function () use ($groupId) {
                // Delete GroupAdvisers records
                DB::table('GroupAdvisers')
                    ->where('GroupID', $groupId)
                    ->delete();

                // Delete GroupMembers records
                DB::table('GroupMembers')
                    ->where('GroupID', $groupId)
                    ->delete();

                // Delete the Group
                DB::table('Groups')
                    ->where('GroupID', $groupId)
                    ->delete();
            });

            return response()->json([
                'message' => 'Group deleted successfully.',
                'data' => [
                    'GroupID' => $groupId,
                    'GroupCode' => $group->GroupCode,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Group not found.',
                'errors' => [
                    'GroupID' => ['The specified group does not exist.']
                ]
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Group deletion failed.',
                'errors' => [
                    'server' => [
                        'An unexpected error occurred.',
                        $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }
    public function getMyGroups(Request $request)
    {
        // Use direct database query to avoid model relationship issues
        $groups = Group::where('AdviserUserID', $request->user()->UserID)->get();

        return response()->json([
            'message' => 'Groups retrieved successfully.',
            'data' => $groups->map(function ($group) {
                // Get members directly from database
                $members = DB::table('GroupMembers')
                    ->where('GroupID', $group->GroupID)
                    ->orderBy('GroupRole', 'desc') // GroupLeader first
                    ->get();

                return [
                    'GroupID' => $group->GroupID,
                    'GroupCode' => $group->GroupCode,
                    'YearLevel' => $group->YearLevel,
                    'Members' => $members->map(function ($member) {
                        $user = User::find($member->StudentUserID);
                        return [
                            'StudentUserID' => $member->StudentUserID,
                            'FullName' => $user ? $user->FullName : 'Unknown',
                            'SchoolID' => $user ? $user->SchoolID : 'Unknown',
                            'GroupRole' => $member->GroupRole,
                        ];
                    }),
                ];
            })
        ], 200);
    }
}
