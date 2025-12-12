<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        if (!$user->hasRole('Student')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Group leader access is restricted to students.'
            ], 403);
        }

        // Get the group ID from the request
        $groupId = $this->getGroupIdFromRequest($request);
        
        if (!$groupId) {
            return response()->json([
                'message' => 'Bad Request.',
                'error' => 'Group ID is required.'
            ], 400);
        }

        // Check if user is the group leader
        $isGroupLeader = DB::table('GroupMembers')
            ->where('GroupID', $groupId)
            ->where('StudentUserID', $user->UserID)
            ->where('GroupRole', 'GroupLeader')
            ->exists();

        if (!$isGroupLeader) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Only group leaders can perform this action.'
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
        
        if ($groupId) {
            return $groupId;
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
