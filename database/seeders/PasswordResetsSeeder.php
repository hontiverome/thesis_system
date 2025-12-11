<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasswordResetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('PasswordResets')->insert([
            ['UserID' => 101, 'ResetToken' => 'dummy_token_123', 'CreatedAt' => '2025-10-25 08:00:00', 'ExpiresAt' => '2025-10-25 09:00:00', 'IsUsed' => false],
        ]);
    }
}
