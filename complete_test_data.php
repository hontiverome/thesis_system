<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Proposal;
use App\Models\Defense;
use App\Models\DefensePanel;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating remaining test data...\n\n";

// Create Course
$course = Course::firstOrCreate(
    ['CourseID' => 'CS-001'],
    [
        'CourseName' => 'Computer Science Research',
    ]
);
echo "✓ Course created/found: {$course->CourseName}\n";

// Create Enrollments
$enrollment1 = Enrollment::firstOrCreate(
    ['EnrollmentID' => 'ENR-001'],
    [
        'GroupID' => 'GRP-001',
        'CourseID' => $course->CourseID,
        'SchoolYear' => '2025-2026',
        'Semester' => 1,
    ]
);

$enrollment2 = Enrollment::firstOrCreate(
    ['EnrollmentID' => 'ENR-002'],
    [
        'GroupID' => 'GRP-002',
        'CourseID' => $course->CourseID,
        'SchoolYear' => '2025-2026',
        'Semester' => 1,
    ]
);
echo "✓ Enrollments created\n";

// Create Proposals
$proposal1 = Proposal::firstOrCreate(
    ['EnrollmentID' => $enrollment1->EnrollmentID],
    [
        'ResearchTitle' => 'Machine Learning for Predictive Analytics',
        'SubmissionDate' => '2026-01-10',
        'Deadline' => '2026-01-31',
        'Status' => 'Pending',
    ]
);

$proposal2 = Proposal::firstOrCreate(
    ['EnrollmentID' => $enrollment2->EnrollmentID],
    [
        'ResearchTitle' => 'Blockchain Technology in Healthcare Systems',
        'SubmissionDate' => '2026-01-12',
        'Deadline' => '2026-01-31',
        'Status' => 'Pending',
    ]
);
echo "✓ Proposals created\n";
echo "  - Proposal {$proposal1->ProposalID}: {$proposal1->ResearchTitle}\n";
echo "  - Proposal {$proposal2->ProposalID}: {$proposal2->ResearchTitle}\n";

// Create Defenses
$defense1 = Defense::firstOrCreate(
    ['DefenseID' => 'DEF-001'],
    [
        'EnrollmentID' => $enrollment1->EnrollmentID,
        'ProposalID' => $proposal1->ProposalID,
        'DefenseType' => 'Proposal Defense',
        'Schedule' => '2026-02-15 10:00:00',
        'OverallVerdict' => 'Pending',
    ]
);

$defense2 = Defense::firstOrCreate(
    ['DefenseID' => 'DEF-002'],
    [
        'EnrollmentID' => $enrollment2->EnrollmentID,
        'ProposalID' => $proposal2->ProposalID,
        'DefenseType' => 'Proposal Defense',
        'Schedule' => '2026-02-16 14:00:00',
        'OverallVerdict' => 'Pending',
    ]
);
echo "✓ Defenses created\n";
echo "  - {$defense1->DefenseID}: {$defense1->Schedule}\n";
echo "  - {$defense2->DefenseID}: {$defense2->Schedule}\n";

// Create Defense Panel Invitations
DefensePanel::firstOrCreate(
    ['DefenseID' => $defense1->DefenseID, 'PanelistUserID' => 1],
    ['Status' => 'Pending']
);

DefensePanel::firstOrCreate(
    ['DefenseID' => $defense1->DefenseID, 'PanelistUserID' => 2],
    ['Status' => 'Pending']
);

DefensePanel::firstOrCreate(
    ['DefenseID' => $defense2->DefenseID, 'PanelistUserID' => 2],
    ['Status' => 'Accepted']
);

DefensePanel::firstOrCreate(
    ['DefenseID' => $defense2->DefenseID, 'PanelistUserID' => 3],
    ['Status' => 'Pending']
);
echo "✓ Panel invitations created\n\n";

echo "✅ All test data ready!\n";
