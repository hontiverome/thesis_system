<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== Test Data Check ===\n\n";

echo "Defenses:\n";
$defenses = DB::table('Defenses')->get(['DefenseID', 'EnrollmentID', 'ProposalID']);
foreach ($defenses as $defense) {
    echo "  {$defense->DefenseID} - EnrollmentID: {$defense->EnrollmentID} - ProposalID: " . ($defense->ProposalID ?? 'NULL') . "\n";
}

echo "\nDefensePanel:\n";
$panels = DB::table('DefensePanel')->get();
foreach ($panels as $panel) {
    echo "  Defense: {$panel->DefenseID} - Panelist: {$panel->PanelistUserID} - Status: {$panel->Status}\n";
}

echo "\nProposals:\n";
$proposals = DB::table('Proposals')->get(['ProposalID', 'EnrollmentID', 'ResearchTitle']);
foreach ($proposals as $proposal) {
    echo "  {$proposal->ProposalID} - {$proposal->ResearchTitle}\n";
}

echo "\n";
