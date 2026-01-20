<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Group;
use Illuminate\Http\Request;

class CourseNavigationController extends Controller
{
    /**
     * GET /api/v1/courses
     * Get all courses with optional filtering
     */
    public function index(Request $request)
    {
        $query = Course::query();
        
        // Optional filters
        if ($request->has('year_level')) {
            $query->where('YearLevel', $request->year_level);
        }
        
        if ($request->has('semester')) {
            $query->where('Semester', $request->semester);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('CourseName', 'like', "%{$search}%")
                  ->orWhere('CourseCode', 'like', "%{$search}%");
            });
        }
        
        $courses = $query->get()->map(function($course) {
            return [
                'CourseID' => $course->CourseID,
                'CourseName' => $course->CourseName,
                'CourseCode' => $course->CourseCode,
                'YearLevel' => $course->YearLevel,
                'Semester' => $course->Semester,
                'enrolled_groups_count' => Enrollment::where('CourseID', $course->CourseID)->distinct('GroupID')->count(),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
    
    /**
     * GET /api/v1/courses/{courseId}/sections
     * Get all sections (groups) enrolled in a specific course
     */
    public function getSections($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Get all groups enrolled in this course
        $enrollments = Enrollment::where('CourseID', $courseId)
            ->with(['group.members.user', 'group.advisers.adviser'])
            ->get();
        
        $sections = $enrollments->map(function($enrollment) {
            $group = $enrollment->group;
            
            return [
                'GroupID' => $group->GroupID,
                'GroupName' => $group->GroupName,
                'EnrollmentID' => $enrollment->EnrollmentID,
                'EnrollmentDate' => $enrollment->EnrollmentDate,
                'members_count' => $group->members->count(),
                'members' => $group->members->map(function($member) {
                    return [
                        'UserID' => $member->user->UserID,
                        'FullName' => $member->user->FullName,
                        'SchoolID' => $member->user->SchoolID,
                    ];
                }),
                'advisers' => $group->advisers->map(function($assignment) {
                    return [
                        'UserID' => $assignment->adviser->UserID,
                        'FullName' => $assignment->adviser->FullName,
                        'SchoolID' => $assignment->adviser->SchoolID,
                    ];
                })
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'course' => [
                    'CourseID' => $course->CourseID,
                    'CourseName' => $course->CourseName,
                    'CourseCode' => $course->CourseCode,
                    'YearLevel' => $course->YearLevel,
                    'Semester' => $course->Semester,
                ],
                'sections' => $sections
            ]
        ]);
    }
    
    /**
     * GET /api/v1/faculty/my-courses
     * Get courses where faculty is adviser to at least one group
     */
    public function getMyCourses(Request $request)
    {
        $user = $request->user();
        
        // Get all groups where user is adviser
        $advisedGroups = $user->advisedGroups()->pluck('GroupID');
        
        // Get enrollments for these groups
        $enrollments = Enrollment::whereIn('GroupID', $advisedGroups)
            ->with('course')
            ->get();
        
        // Group by course
        $courses = $enrollments->groupBy('CourseID')->map(function($courseEnrollments) {
            $course = $courseEnrollments->first()->course;
            
            return [
                'CourseID' => $course->CourseID,
                'CourseName' => $course->CourseName,
                'CourseCode' => $course->CourseCode,
                'YearLevel' => $course->YearLevel,
                'Semester' => $course->Semester,
                'my_groups_count' => $courseEnrollments->count(),
            ];
        })->values();
        
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
}
