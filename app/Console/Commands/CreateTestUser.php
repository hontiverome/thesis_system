<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    protected $signature = 'app:create-test-users';
    protected $description = 'Create test users for development';

    public function handle()
    {
        $users = [
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

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'is_admin' => $userData['is_admin'],
                    'email_verified_at' => now(),
                ]
            );

            $this->info("User created/updated: {$user->email} (Password: {$userData['password']})");
        }

        $this->info("\nTest users created successfully!");
        $this->line("Admin: admin@example.com / password");
        $this->line("User: user@example.com / password");
    }
}