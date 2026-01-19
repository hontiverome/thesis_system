<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserCourseController extends Controller
{
    public function getAdviserCourses(Request $request)
    {
        // Kunin lahat ng courses
        $courses = DB::table('courses')->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ], 200);
    }
}