<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProposalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // First, create enrollments for the groups
        DB::table('Enrollments')->insert([
            [
                'EnrollmentID' => 'E001',
                'GroupID' => 'G001',
                'CourseID' => 'C1', // Methods of Research
                'SchoolYear' => '2025-2026',
                'Semester' => '1st',
            ],
            [
                'EnrollmentID' => 'E002',
                'GroupID' => 'G002',
                'CourseID' => 'C2', // Design Project 1
                'SchoolYear' => '2025-2026',
                'Semester' => '1st',
            ],
            [
                'EnrollmentID' => 'E003',
                'GroupID' => 'G003',
                'CourseID' => 'C1', // Methods of Research
                'SchoolYear' => '2025-2026',
                'Semester' => '1st',
            ],
        ]);

        // Now create proposals linked to enrollments
        DB::table('Proposals')->insert([
            [
                'ProposalID' => 'P001',
                'EnrollmentID' => 'E001',
                'ResearchTitle' => 'AI-Powered Student Information System for PUP',
                'SubmissionDate' => $now->copy()->subDays(5)->format('Y-m-d'),
                'Deadline' => $now->copy()->addDays(25)->format('Y-m-d'),
                'Status' => 'pending',
            ],
            [
                'ProposalID' => 'P002',
                'EnrollmentID' => 'E002',
                'ResearchTitle' => 'Smart Campus Navigation System Using Mobile Technology',
                'SubmissionDate' => $now->copy()->subDays(10)->format('Y-m-d'),
                'Deadline' => $now->copy()->addDays(20)->format('Y-m-d'),
                'Status' => 'approved',
            ],
            [
                'ProposalID' => 'P003',
                'EnrollmentID' => 'E003',
                'ResearchTitle' => 'Online Thesis Management and Archiving System',
                'SubmissionDate' => $now->copy()->subDays(3)->format('Y-m-d'),
                'Deadline' => $now->copy()->addDays(27)->format('Y-m-d'),
                'Status' => 'pending',
            ],
        ]);
    }
}
