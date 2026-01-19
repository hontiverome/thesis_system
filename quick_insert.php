<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Creating complete test data...\n\n";

// 1. Groups
try {
    DB::table('Groups')->insert([
        ['GroupID'=>'GRP-001','GroupCode'=>'CS-G1','YearLevel'=>4,'AdviserUserID'=>1],
        ['GroupID'=>'GRP-002','GroupCode'=>'CS-G2','YearLevel'=>4,'AdviserUserID'=>2]
    ]);
    echo "✓ Groups\n";
} catch(\Exception $e) { echo "  (Groups exist)\n"; }

// 2. GroupAdvisers  
try {
    DB::table('GroupAdvisers')->insert([
        ['GroupID'=>'GRP-001','AdviserUserID'=>1],
        ['GroupID'=>'GRP-002','AdviserUserID'=>2]
    ]);
    echo "✓ GroupAdvisers\n";
} catch(\Exception $e) { echo "  (GroupAdvisers exist)\n"; }

// 3. GroupMembers
try {
    DB::table('GroupMembers')->insert([
        ['GroupID'=>'GRP-001','StudentUserID'=>4,'GroupRole'=>'Leader'],
        ['GroupID'=>'GRP-001','StudentUserID'=>5,'GroupRole'=>'Member'],
        ['GroupID'=>'GRP-002','StudentUserID'=>7,'GroupRole'=>'Leader'],
        ['GroupID'=>'GRP-002','StudentUserID'=>8,'GroupRole'=>'Member']
    ]);
    echo "✓ GroupMembers\n";
} catch(\Exception $e) { echo "  (GroupMembers exist)\n"; }

// 4. Course
try {
    DB::table('Courses')->insert(['CourseID'=>'CS-001','CourseName'=>'Computer Science Research']);
    echo "✓ Course\n";
} catch(\Exception $e) { echo "  (Course exists)\n"; }

// 5. Enrollments
try {
    DB::table('Enrollments')->insert([
        ['EnrollmentID'=>'ENR-001','GroupID'=>'GRP-001','CourseID'=>'CS-001','SchoolYear'=>'2025-2026','Semester'=>1],
        ['EnrollmentID'=>'ENR-002','GroupID'=>'GRP-002','CourseID'=>'CS-001','SchoolYear'=>'2025-2026','Semester'=>1]
    ]);
    echo "✓ Enrollments\n";
} catch(\Exception $e) { echo "  (Enrollments exist)\n"; }

// 6. Proposals
try {
    DB::table('Proposals')->insert([
        ['ProposalID'=>'PROP-001','EnrollmentID'=>'ENR-001','ResearchTitle'=>'Machine Learning for Predictive Analytics','SubmissionDate'=>'2026-01-10','Deadline'=>'2026-01-31','Status'=>'Pending'],
        ['ProposalID'=>'PROP-002','EnrollmentID'=>'ENR-002','ResearchTitle'=>'Blockchain Technology in Healthcare Systems','SubmissionDate'=>'2026-01-12','Deadline'=>'2026-01-31','Status'=>'Pending']
    ]);
    echo "✓ Proposals\n";
} catch(\Exception $e) { echo "  (Proposals exist)\n"; }

// 7. Defenses
try {
    DB::table('Defenses')->insert([
        ['DefenseID'=>'DEF-001','EnrollmentID'=>'ENR-001','ProposalID'=>'PROP-001','DefenseType'=>'Proposal Defense','Schedule'=>'2026-02-15 10:00:00','OverallVerdict'=>'Pending'],
        ['DefenseID'=>'DEF-002','EnrollmentID'=>'ENR-002','ProposalID'=>'PROP-002','DefenseType'=>'Proposal Defense','Schedule'=>'2026-02-16 14:00:00','OverallVerdict'=>'Pending']
    ]);
    echo "✓ Defenses\n";
} catch(\Exception $e) { echo "  (Defenses exist)\n"; }

// 8. DefensePanel
try {
    DB::table('DefensePanel')->insert([
        ['DefenseID'=>'DEF-001','PanelistUserID'=>1,'Status'=>'Pending'],
        ['DefenseID'=>'DEF-001','PanelistUserID'=>2,'Status'=>'Pending'],
        ['DefenseID'=>'DEF-002','PanelistUserID'=>2,'Status'=>'Accepted'],
        ['DefenseID'=>'DEF-002','PanelistUserID'=>3,'Status'=>'Pending']
    ]);
    echo "✓ DefensePanel\n";
} catch(\Exception $e) { echo "  (DefensePanel exist)\n"; }

echo "\n══════════════════════════════════════════\n";
echo "✅ ALL TEST DATA READY!\n";
echo "══════════════════════════════════════════\n\n";

echo "NOW RETRY IN POSTMAN:\n";
echo "  GET /api/v1/faculty/proposals\n";
echo "  GET /api/v1/faculty/proposals/PROP-001\n";
echo "  PATCH /api/v1/proposals/PROP-001/verdict\n";
echo "\nUse ProposalID: PROP-001 or PROP-002\n";
echo "Use DefenseID: DEF-001 or DEF-002\n";

