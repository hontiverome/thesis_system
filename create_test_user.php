<?php

require __DIR__.'/vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test user data
$testUsers = [
    [
        'name' => 'Test Admin',
        'email' => 'admin@example.com',
        'password' => 'password',
        'is_admin' => true
    ],
    [
        'name' => 'Test User',
        'email' => 'user@example.com',
        'password' => 'password',
        'is_admin' => false
    ]
];

foreach ($testUsers as $userData) {
    // Check if user exists
    $user = User::where('email', $userData['email'])->first();
    
    if ($user) {
        echo "Updating user: {$userData['email']}\n";
        $user->update([
            'name' => $userData['name'],
            'password' => Hash::make($userData['password']),
            'is_admin' => $userData['is_admin']
        ]);
    } else {
        echo "Creating user: {$userData['email']}\n";
        User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'is_admin' => $userData['is_admin'],
            'email_verified_at' => now(),
        ]);
    }
}

echo "Test users created/updated successfully!\n";
