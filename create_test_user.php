<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// Bootstrap the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// --- Role and User Setup ---

try {
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $studentRole = Role::where('name', 'student')->firstOrFail();
} catch (ModelNotFoundException $e) {
    echo "Error: Required roles ('admin', 'student') not found. Please seed the roles table.\n";
    exit(1);
}

$testUsers = [
    [
        'first_name' => 'Test',
        'last_name' => 'Admin',
        'id_number' => 'ADMIN-001',
        'email' => 'admin@example.com',
        'password' => 'password',
        'birth_date' => '2000-01-01',
        'role' => $adminRole,
    ],
    [
        'first_name' => 'Test',
        'last_name' => 'User',
        'id_number' => 'USER-001',
        'email' => 'user@example.com',
        'password' => 'password',
        'birth_date' => '2000-01-01',
        'role' => $studentRole,
    ]
];

foreach ($testUsers as $userData) {
    $user = User::updateOrCreate(
        ['email' => $userData['email']],
        [
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'id_number' => $userData['id_number'],
            'password' => Hash::make($userData['password']),
            'birth_date' => $userData['birth_date'],
            'email_verified_at' => now(),
        ]
    );

    if ($user->wasRecentlyCreated) {
        echo "Creating user: {$userData['email']} with role '{$userData['role']->name}'\n";
    } else {
        echo "Updating user: {$userData['email']} and ensuring role '{$userData['role']->name}'\n";
    }
    $user->roles()->sync([$userData['role']->id]);
}

echo "Test users created/updated successfully!\n";