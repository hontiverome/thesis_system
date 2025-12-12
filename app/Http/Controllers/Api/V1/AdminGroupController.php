<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGroupController extends Controller
{
    /**
     * Assign course to a group (F-017).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignCourse(Request $request, $groupId)
    {
        try {
            $user = $request->user();
            if (!$user->hasRole('Administrator')) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => 'Only Admins can assign courses to groups.'
                ], 403);
            }

            // Validate request
            $request->validate([
                'CourseID' => 'required|string|exists:Courses,CourseID',
                'SchoolYear' => 'required|string|max:10',
                'Semester' => 'required|string|max:10',
            ]);

            // Check if group exists
            $group = Group::find($groupId);
            if (!$group) {
                return response()->json([
                    'message' => 'Group not found.',
                    'errors' => [
                        'GroupID' => ['The specified group does not exist.']
                    ]
                ], 404);
            }

            // Get course details
            $course = Course::find($request->CourseID);
            if (!$course) {
                return response()->json([
                    'message' => 'Course not found.',
                    'errors' => [
                        'CourseID' => ['The specified course does not exist.']
                    ]
                ], 404);
            }

            // Check if enrollment already exists for this group
            $existingEnrollment = DB::table('Enrollments')
                ->where('GroupID', $groupId)
                ->first();

            if ($existingEnrollment) {
                // Update existing enrollment
                DB::table('Enrollments')
                    ->where('GroupID', $groupId)
                    ->update([
                        'CourseID' => $request->CourseID,
                        'SchoolYear' => $request->SchoolYear,
                        'Semester' => $request->Semester,
                    ]);

                $enrollmentId = $existingEnrollment->EnrollmentID;
                $action = 'updated';
            } else {
                // Create new enrollment
                // Generate unique EnrollmentID
                $lastEnrollment = DB::table('Enrollments')
                    ->orderBy('EnrollmentID', 'desc')
                    ->first();
                $lastId = $lastEnrollment ? (int)str_replace('E', '', $lastEnrollment->EnrollmentID) : 0;
                $newId = $lastId + 1;
                $enrollmentId = 'E' . str_pad($newId, 3, '0', STR_PAD_LEFT);

                // Create new enrollment record
                DB::table('Enrollments')->insert([
                    'EnrollmentID' => $enrollmentId,
                    'GroupID' => $groupId,
                    'CourseID' => $request->CourseID,
                    'SchoolYear' => $request->SchoolYear,
                    'Semester' => $request->Semester,
                ]);

                $action = 'created';
            }

            return response()->json([
                'message' => 'Course assignment successful.',
                'data' => [
                    'GroupID' => $groupId,
                    'GroupCode' => $group->GroupCode,
                    'CourseID' => $request->CourseID,
                    'CourseName' => $course->CourseName,
                    'SchoolYear' => $request->SchoolYear,
                    'Semester' => $request->Semester,
                    'EnrollmentID' => $enrollmentId,
                    'Action' => $action,
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Course assignment failed.',
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
     * Get all groups with their course assignments (F-017 admin panel).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsWithCourses(Request $request)
    {
        try {
            $user = $request->user();

            // Check if user is Super Admin
            if (!$user->hasRole('Administrator')) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => 'Only Admins can view group course assignments.'
                ], 403);
            }

            // Get all groups with their course assignments
            $groups = DB::table('Groups')
                ->leftJoin('Enrollments', 'Groups.GroupID', '=', 'Enrollments.GroupID')
                ->leftJoin('Courses', 'Enrollments.CourseID', '=', 'Courses.CourseID')
                ->leftJoin('Users', 'Groups.AdviserUserID', '=', 'Users.UserID')
                ->select(
                    'Groups.GroupID',
                    'Groups.GroupCode',
                    'Groups.YearLevel',
                    'Groups.AdviserUserID',
                    'Users.FullName as AdviserName',
                    'Enrollments.EnrollmentID',
                    'Enrollments.CourseID',
                    'Enrollments.SchoolYear',
                    'Enrollments.Semester',
                    'Courses.CourseName'
                )
                ->orderBy('Groups.GroupCode')
                ->get();

            // Format the response to remove null course information
            $formattedGroups = $groups->map(function ($group) {
                $result = [
                    'GroupID' => $group->GroupID,
                    'GroupCode' => $group->GroupCode,
                    'YearLevel' => $group->YearLevel,
                    'AdviserUserID' => $group->AdviserUserID,
                    'AdviserName' => $group->AdviserName,
                    'HasCourse' => !is_null($group->EnrollmentID),
                ];

                // Only include course information if it exists
                if ($group->EnrollmentID) {
                    $result['Course'] = [
                        'EnrollmentID' => $group->EnrollmentID,
                        'CourseID' => $group->CourseID,
                        'CourseName' => $group->CourseName,
                        'SchoolYear' => $group->SchoolYear,
                        'Semester' => $group->Semester,
                    ];
                }

                return $result;
            });

            return response()->json([
                'message' => 'Groups with courses retrieved successfully.',
                'data' => $formattedGroups
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve groups.',
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
     * Get available courses for assignment (F-017 dropdown).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableCourses(Request $request)
    {
        try {
            $user = $request->user();

            // Check if user is Super Admin
            if (!$user->hasRole('Super Admin')) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => 'Only Super Admins can view available courses.'
                ], 403);
            }

            $courses = Course::orderBy('CourseID')->get();

            return response()->json([
                'message' => 'Available courses retrieved successfully.',
                'data' => $courses
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve courses.',
                'errors' => [
                    'server' => [
                        'An unexpected error occurred.',
                        $e->getMessage(),
                    ],
                ],
            ], 500);
        }
    }
}
