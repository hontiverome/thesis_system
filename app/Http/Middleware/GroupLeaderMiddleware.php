<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\GroupMember;

class GroupLeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required.'
            ], 401);
        }

        // Check if user is a student
        if (!$user->hasRole('Student') && !$user->hasRole('GroupLeader')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Group leader access is restricted to students.'
            ], 403);
        }

        // Get the group ID from the request
        $groupId = $this->getGroupIdFromRequest($request);
        
        // Check if user is a member of the requested group
        $member = null;
        if ($groupId && $groupId !== '{groupId}') {
            $member = DB::table('GroupMembers')
                ->where('GroupID', $groupId)
                ->where('StudentUserID', $user->UserID)
                ->first();
        }

        // Fallback: If not found (or invalid ID), try to find the user's actual group
        if (!$member) {
            $userGroup = $user->groups()->first();
            if ($userGroup) {
                $groupId = $userGroup->GroupID;
                $request->route()->setParameter('groupId', $groupId);
                
                $member = DB::table('GroupMembers')
                    ->where('GroupID', $groupId)
                    ->where('StudentUserID', $user->UserID)
                    ->first();
            }
        }

        if (!$member) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'You are not a member of this group.',
                'debug' => [
                    'received_group_id' => $groupId,
                    'user_id' => $user->UserID
                ]
            ], 403);
        }

        if (!in_array($member->GroupRole, ['GroupLeader', 'Leader'])) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Only group leaders can perform this action. Your role is: ' . $member->GroupRole
            ], 403);
        }

        return $next($request);
    }

    /**
     * Extract GroupID from the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    private function getGroupIdFromRequest(Request $request)
    {
        // Try to get GroupID from route parameters
        $groupId = $request->route('groupId');
        
        if ($groupId instanceof \Illuminate\Database\Eloquent\Model) {
            return $groupId->getKey();
        }

        if ($groupId) {
            return is_string($groupId) ? trim($groupId) : $groupId;
        }

        // Try to get GroupID from request body (for POST/PUT requests)
        if ($request->has('GroupID')) {
            return $request->input('GroupID');
        }

        // Try to get GroupID from query parameters
        if ($request->has('group_id')) {
            return $request->input('group_id');
        }

        return null;
    }
}
