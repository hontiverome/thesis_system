<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserCourseController extends Controller
{
    public function getAdviserCourses(Request $request)
    {
        // Kukunin natin ang courses mula sa database
        $courses = DB::table('courses')
                    ->select('CourseID', 'CourseName', 'PrerequisiteCourseID')
                    ->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ], 200);
    }
}