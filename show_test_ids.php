<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\Proposal;
use App\Models\Defense;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "═══════════════════════════════════════════════════════════\n";
echo "            TEST DATA IDs FOR POSTMAN TESTING\n";
echo "═══════════════════════════════════════════════════════════\n\n";

echo "📋 PROPOSALS:\n";
echo "─────────────────────────────────────────────────────────────\n";
$proposals = Proposal::with('enrollment.group')->get();
foreach ($proposals as $proposal) {
    echo "ProposalID: {$proposal->ProposalID}\n";
    echo "  Title: {$proposal->ResearchTitle}\n";
    echo "  Status: {$proposal->Status}\n";
    echo "  Group: {$proposal->enrollment->group->GroupCode}\n\n";
}

echo "\n🛡️ DEFENSES:\n";
echo "─────────────────────────────────────────────────────────────\n";
$defenses = Defense::with('proposal')->get();
foreach ($defenses as $defense) {
    echo "DefenseID: {$defense->DefenseID}\n";
    echo "  Type: {$defense->DefenseType}\n";
    echo "  Schedule: {$defense->Schedule}\n";
    echo "  Proposal: {$defense->proposal->ResearchTitle}\n\n";
}

echo "\n👨‍🏫 USERS FOR LOGIN:\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "faculty1@test.com / password123 (Adviser of GRP-001)\n";
echo "faculty2@test.com / password123 (Adviser of GRP-002)\n";
echo "coordinator@test.com / password123 (Can see all)\n";

echo "\n✅ Server is running at: http://127.0.0.1:8000\n";
echo "📘 See POSTMAN_TESTING_GUIDE.md for detailed instructions\n\n";
