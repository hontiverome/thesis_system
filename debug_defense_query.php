<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Defense;
use Illuminate\Support\Facades\DB;

echo "\n=== Debug Defense Query ===\n\n";

// Raw query
echo "Raw query:\n";
$raw = DB::table('Defenses')->where('DefenseID', 'DEF-001')->first();
var_dump($raw);

echo "\n\nEloquent query:\n";
$defense = Defense::where('DefenseID', 'DEF-001')->first();
var_dump($defense);

echo "\n\nEloquent find:\n";
$defense2 = Defense::find('DEF-001');
var_dump($defense2);

echo "\n";
