<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Defense;

echo "\n=== Defense Relationships Debug ===\n\n";

$defense = Defense::find('DEF-001');

echo "Defense found: {$defense->DefenseID}\n";
echo "ProposalID: {$defense->ProposalID}\n";
echo "EnrollmentID: {$defense->EnrollmentID}\n\n";

echo "Loading proposal...\n";
$proposal = $defense->proposal;
if ($proposal) {
    echo "✓ Proposal loaded: {$proposal->ProposalID} - {$proposal->ResearchTitle}\n";
} else {
    echo "✗ Proposal is NULL\n";
    
    // Try manual query
    echo "\nTrying manual query...\n";
    $manualProposal = \App\Models\Proposal::where('ProposalID', $defense->ProposalID)->first();
    if ($manualProposal) {
        echo "✓ Manual query found: {$manualProposal->ProposalID}\n";
    } else {
        echo "✗ Manual query also failed\n";
    }
}

echo "\n";
