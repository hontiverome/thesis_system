<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DefensePanel;

echo "\n=== Defense Panel Invitations ===\n\n";

$panels = DefensePanel::with(['defense.proposal', 'panelist'])->get();

if ($panels->isEmpty()) {
    echo "No panel invitations found!\n\n";
} else {
    foreach ($panels as $panel) {
        echo "PanelID: " . ($panel->PanelID ?? 'N/A') . "\n";
        echo "DefenseID: {$panel->DefenseID}\n";
        echo "Proposal: {$panel->defense->proposal->ResearchTitle}\n";
        echo "Panelist: {$panel->panelist->FullName} ({$panel->panelist->SchoolID})\n";
        echo "Role: " . ($panel->Role ?? 'N/A') . "\n";
        echo "Status: {$panel->Status}\n";
        echo "InvitationStatus: " . ($panel->InvitationStatus ?? 'N/A') . "\n";
        echo "---\n";
    }
}

echo "\nTotal invitations: " . $panels->count() . "\n\n";
