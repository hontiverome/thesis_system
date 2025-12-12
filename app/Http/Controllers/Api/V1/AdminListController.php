<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
    /**
     * List all users with filtering and search capabilities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers(Request $request)
    {
        // Check if authenticated user has admin role
        if (!$request->user() || !$request->user()->hasRole('Administrator')) {
            return response()->json([
                'message' => 'Forbidden.',
                'error' => 'Administrator access required.'
            ], 403);
        }

        $query = User::with(['roles', 'facultyDetail']);

        // Filter by role
        if ($request->filled('role_filter')) {
            $roleName = $request->role_filter;
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('RoleName', $roleName);
            });
        }

        // Search by name or email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('FullName', 'like', "%{$searchTerm}%")
                  ->orWhere('Email', 'like', "%{$searchTerm}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json([
            'message' => 'Users retrieved successfully.',
            'data' => [
                'users' => $users->map(function ($user) {
                    return [
                        'UserID' => $user->UserID,
                        'SchoolID' => $user->SchoolID,
                        'FullName' => $user->FullName,
                        'Email' => $user->Email,
                        'Roles' => $user->roles->pluck('RoleName'),
                        'FacultyType' => $user->facultyDetail?->FacultyType ?? null,
                        'Status' => 'Active',
                    ];
                }),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ],
            ],
        ], 200);
    }
}
