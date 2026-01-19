<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdviserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required.'
            ], 401);
        }

        $user = Auth::user();

        // Check if user has Adviser role (Admin is exempt)
        if (!$user->hasRole('Adviser') && !$user->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Adviser access required.'
            ], 403);
        }

        // For Admin, bypass all restrictions
        if ($user->hasRole('Administrator')) {
            return $next($request);
        }

        // For Adviser, enforce access control rules
        $this->enforceAdviserAccessControl($request, $user);

        return $next($request);
    }

    /**
     * Enforce adviser-specific access control rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    private function enforceAdviserAccessControl(Request $request, $user)
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        $routeMethod = $request->method();
        $routeUri = $request->path();

        // Skip enforcement for adviser's own groups and members
        if (str_contains($routeUri, 'adviser/groups')) {
            $this->validateAdviserGroupAccess($request, $user);
        }

        // Enforce proposal access control for advisers
        if (str_contains($routeUri, 'proposals') && $routeMethod === 'GET') {
            $this->validateAdviserProposalAccess($request, $user);
        }
    }

    /**
     * Validate that adviser can only access their own groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    private function validateAdviserGroupAccess(Request $request, $user)
    {
        $groupId = $request->route('groupId');
        
        if ($groupId) {
            $group = \App\Models\Group::find($groupId);
            
            if (!$group || $group->AdviserUserID !== $user->UserID) {
                abort(403, 'Forbidden: You can only manage your own groups.');
            }
        }
    }

    /**
     * Validate that adviser can only access proposals from their groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    private function validateAdviserProposalAccess(Request $request, $user)
    {
        $proposalId = $request->route('proposalId');
        
        if ($proposalId) {
            $proposal = \App\Models\Proposal::find($proposalId);
            
            if (!$proposal) {
                abort(404, 'Proposal not found.');
            }

            // Check if proposal belongs to adviser's group
            $enrollment = $proposal->enrollment;
            if (!$enrollment) {
                abort(403, 'Forbidden: Invalid proposal enrollment.');
            }

            $group = $enrollment->group;
            if (!$group || $group->AdviserUserID !== $user->UserID) {
                abort(403, 'Forbidden: You can only access proposals from your groups.');
            }
        }
    }
}
