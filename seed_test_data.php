<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\User;
use App\Models\Role;
use App\Models\Group;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Proposal;
use App\Models\Defense;
use App\Models\DefensePanel;
use App\Models\GroupAdviser;
use App\Models\GroupMember;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating test data for API testing...\n\n";

// Get roles
$adminRole = Role::where('RoleName', 'admin')->first();
$facultyRole = Role::where('RoleName', 'faculty')->first();
$studentRole = Role::where('RoleName', 'student')->first();

if (!$adminRole || !$facultyRole || !$studentRole) {
    echo "Error: Roles not found. Please seed roles first.\n";
    exit(1);
}

// Create Faculty Users
echo "Creating faculty users...\n";
$faculty1 = User::create([
    'SchoolID' => 'FAC-001',
    'FullName' => 'Dr. John Smith',
    'Email' => 'faculty1@test.com',
    'BirthDate' => '1980-01-15',
    'PasswordHash' => Hash::make('password123'),
]);
$faculty1->roles()->attach($facultyRole->RoleID);

$faculty2 = User::create([
    'SchoolID' => 'FAC-002',
    'FullName' => 'Prof. Jane Doe',
    'Email' => 'faculty2@test.com',
    'BirthDate' => '1982-05-20',
    'PasswordHash' => Hash::make('password123'),
]);
$faculty2->roles()->attach($facultyRole->RoleID);

$coordinator = User::create([
    'SchoolID' => 'COORD-001',
    'FullName' => 'Dr. Research Coordinator',
    'Email' => 'coordinator@test.com',
    'BirthDate' => '1975-03-10',
    'PasswordHash' => Hash::make('password123'),
]);
$coordinator->roles()->attach($facultyRole->RoleID);

echo "✓ Created 3 faculty users\n";

// Create Student Users
echo "Creating student users...\n";
$students = [];
for ($i = 1; $i <= 6; $i++) {
    $student = User::create([
        'SchoolID' => "STU-00{$i}",
        'FullName' => "Student {$i}",
        'Email' => "student{$i}@test.com",
        'BirthDate' => '2000-01-01',
        'PasswordHash' => Hash::make('password123'),
    ]);
    $student->roles()->attach($studentRole->RoleID);
    $students[] = $student;
}
echo "✓ Created 6 student users\n";

// Create Course
echo "Creating courses...\n";
$course = Course::create([
    'CourseID' => 'CS-001',
    'CourseName' => 'Computer Science Research',
    'CourseCode' => 'CS498',
    'YearLevel' => 4,
    'Semester' => 1,
]);
echo "✓ Created course: {$course->CourseName}\n";

// Create Groups
echo "Creating groups...\n";
$group1 = Group::create([
    'GroupID' => 'GRP-001',
    'GroupCode' => 'CS-G1',
    'YearLevel' => 4,
]);

$group2 = Group::create([
    'GroupID' => 'GRP-002',
    'GroupCode' => 'CS-G2',
    'YearLevel' => 4,
]);
echo "✓ Created 2 groups\n";

// Assign advisers to groups
echo "Assigning advisers...\n";
GroupAdviser::create([
    'GroupID' => $group1->GroupID,
    'AdviserUserID' => $faculty1->UserID,
]);

GroupAdviser::create([
    'GroupID' => $group2->GroupID,
    'AdviserUserID' => $faculty2->UserID,
]);
echo "✓ Assigned advisers to groups\n";

// Add members to groups
echo "Adding group members...\n";
GroupMember::create([
    'GroupID' => $group1->GroupID,
    'StudentUserID' => $students[0]->UserID,
    'GroupRole' => 'Leader',
]);

GroupMember::create([
    'GroupID' => $group1->GroupID,
    'StudentUserID' => $students[1]->UserID,
    'GroupRole' => 'Member',
]);

GroupMember::create([
    'GroupID' => $group1->GroupID,
    'StudentUserID' => $students[2]->UserID,
    'GroupRole' => 'Member',
]);

GroupMember::create([
    'GroupID' => $group2->GroupID,
    'StudentUserID' => $students[3]->UserID,
    'GroupRole' => 'Leader',
]);

GroupMember::create([
    'GroupID' => $group2->GroupID,
    'StudentUserID' => $students[4]->UserID,
    'GroupRole' => 'Member',
]);

GroupMember::create([
    'GroupID' => $group2->GroupID,
    'StudentUserID' => $students[5]->UserID,
    'GroupRole' => 'Member',
]);
echo "✓ Added 6 students to groups\n";

// Create Enrollments
echo "Creating enrollments...\n";
$enrollment1 = Enrollment::create([
    'EnrollmentID' => 'ENR-001',
    'GroupID' => $group1->GroupID,
    'CourseID' => $course->CourseID,
    'SchoolYear' => '2025-2026',
    'Semester' => 1,
]);

$enrollment2 = Enrollment::create([
    'EnrollmentID' => 'ENR-002',
    'GroupID' => $group2->GroupID,
    'CourseID' => $course->CourseID,
    'SchoolYear' => '2025-2026',
    'Semester' => 1,
]);
echo "✓ Created 2 enrollments\n";

// Create Proposals
echo "Creating proposals...\n";
$proposal1 = Proposal::create([
    'EnrollmentID' => $enrollment1->EnrollmentID,
    'ResearchTitle' => 'Machine Learning for Predictive Analytics',
    'SubmissionDate' => '2026-01-10',
    'Deadline' => '2026-01-31',
    'Status' => 'Pending',
]);

$proposal2 = Proposal::create([
    'EnrollmentID' => $enrollment2->EnrollmentID,
    'ResearchTitle' => 'Blockchain Technology in Healthcare Systems',
    'SubmissionDate' => '2026-01-12',
    'Deadline' => '2026-01-31',
    'Status' => 'Pending',
]);
echo "✓ Created 2 proposals\n";

// Create Defenses
echo "Creating defenses...\n";
$defense1 = Defense::create([
    'DefenseID' => 'DEF-001',
    'EnrollmentID' => $enrollment1->EnrollmentID,
    'ProposalID' => $proposal1->ProposalID,
    'DefenseType' => 'Proposal Defense',
    'Schedule' => '2026-02-15 10:00:00',
    'OverallVerdict' => 'Pending',
]);

$defense2 = Defense::create([
    'DefenseID' => 'DEF-002',
    'EnrollmentID' => $enrollment2->EnrollmentID,
    'ProposalID' => $proposal2->ProposalID,
    'DefenseType' => 'Proposal Defense',
    'Schedule' => '2026-02-16 14:00:00',
    'OverallVerdict' => 'Pending',
]);
echo "✓ Created 2 defenses\n";

// Create Defense Panel Invitations
echo "Creating panel invitations...\n";
DefensePanel::create([
    'DefenseID' => $defense1->DefenseID,
    'PanelistUserID' => $faculty1->UserID,
    'Status' => 'Pending',
]);

DefensePanel::create([
    'DefenseID' => $defense1->DefenseID,
    'PanelistUserID' => $faculty2->UserID,
    'Status' => 'Pending',
]);

DefensePanel::create([
    'DefenseID' => $defense2->DefenseID,
    'PanelistUserID' => $faculty2->UserID,
    'Status' => 'Accepted',
]);

DefensePanel::create([
    'DefenseID' => $defense2->DefenseID,
    'PanelistUserID' => $coordinator->UserID,
    'Status' => 'Pending',
]);
echo "✓ Created panel invitations\n";

echo "\n=== Test Data Created Successfully! ===\n\n";
echo "TEST CREDENTIALS:\n";
echo "─────────────────────────────────────────\n";
echo "Faculty 1 (Adviser of Group 1):\n";
echo "  Email: faculty1@test.com\n";
echo "  Password: password123\n";
echo "  UserID: {$faculty1->UserID}\n\n";

echo "Faculty 2 (Adviser of Group 2):\n";
echo "  Email: faculty2@test.com\n";
echo "  Password: password123\n";
echo "  UserID: {$faculty2->UserID}\n\n";

echo "Coordinator:\n";
echo "  Email: coordinator@test.com\n";
echo "  Password: password123\n";
echo "  UserID: {$coordinator->UserID}\n\n";

echo "SAMPLE IDS FOR TESTING:\n";
echo "─────────────────────────────────────────\n";
echo "Proposal 1 ID: {$proposal1->ProposalID}\n";
echo "Proposal 2 ID: {$proposal2->ProposalID}\n";
echo "Defense 1 ID: {$defense1->DefenseID}\n";
echo "Defense 2 ID: {$defense2->DefenseID}\n";
echo "Group 1 ID: {$group1->GroupID}\n";
echo "Group 2 ID: {$group2->GroupID}\n";

echo "\n✓ Ready for Postman testing!\n";
