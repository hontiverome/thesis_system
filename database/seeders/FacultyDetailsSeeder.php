<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultyDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('FacultyDetails')->insert([
            ['FacultyDetailID' => 'F1', 'UserID' => 113, 'FacultyType' => 'Full-Time'],
            ['FacultyDetailID' => 'F2', 'UserID' => 114, 'FacultyType' => 'Part-Time'],
            ['FacultyDetailID' => 'F3', 'UserID' => 115, 'FacultyType' => 'Full-Time'],
            ['FacultyDetailID' => 'F4', 'UserID' => 116, 'FacultyType' => 'Part-Time'],
        ]);
    }
}
