<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Courses')->insert([
            ['CourseID' => 'C1', 'CourseName' => 'Methods of Research', 'PrerequisiteCourseID' => null],
            ['CourseID' => 'C2', 'CourseName' => 'Design Project 1', 'PrerequisiteCourseID' => 'C1'],
            ['CourseID' => 'C3', 'CourseName' => 'Design Project 2', 'PrerequisiteCourseID' => 'C2'],
        ]);
    }
}
