<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Proposal;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\GroupAdviser;

echo "Database Check:\n";
echo "===============\n";
echo "Proposals: " . Proposal::count() . "\n";
echo "Enrollments: " . Enrollment::count() . "\n";
echo "Groups: " . Group::count() . "\n";
echo "GroupAdvisers: " . GroupAdviser::count() . "\n\n";

if (Proposal::count() > 0) {
    echo "Proposal IDs:\n";
    foreach (Proposal::all() as $p) {
        echo "  - ProposalID: {$p->ProposalID}, Title: {$p->ResearchTitle}\n";
    }
}

if (GroupAdviser::count() > 0) {
    echo "\nGroup Advisers:\n";
    foreach (GroupAdviser::all() as $ga) {
        echo "  - GroupID: {$ga->GroupID}, AdviserUserID: {$ga->AdviserUserID}\n";
    }
}
