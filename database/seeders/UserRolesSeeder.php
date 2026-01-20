<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('UserRoles')->insert([
            ['UserID' => 101, 'RoleID' => 1],
            ['UserID' => 102, 'RoleID' => 1],
            ['UserID' => 103, 'RoleID' => 1],
            ['UserID' => 104, 'RoleID' => 1],
            ['UserID' => 105, 'RoleID' => 1],
            ['UserID' => 106, 'RoleID' => 1],
            ['UserID' => 107, 'RoleID' => 1],
            ['UserID' => 108, 'RoleID' => 1],
            ['UserID' => 109, 'RoleID' => 1],
            ['UserID' => 110, 'RoleID' => 1],
            ['UserID' => 111, 'RoleID' => 1],
            ['UserID' => 112, 'RoleID' => 1],
            ['UserID' => 113, 'RoleID' => 4], // Faculty
            ['UserID' => 113, 'RoleID' => 3], // Adviser
            ['UserID' => 114, 'RoleID' => 4], // Faculty
            ['UserID' => 114, 'RoleID' => 3], // Adviser
            ['UserID' => 115, 'RoleID' => 4], // Faculty
            ['UserID' => 116, 'RoleID' => 4], // Faculty
            ['UserID' => 117, 'RoleID' => 6], // Administrator
        ]);
    }
}
