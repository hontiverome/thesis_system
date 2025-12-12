<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Roles')->insert([
            ['RoleID' => 1, 'RoleName' => 'Student'],
            ['RoleID' => 2, 'RoleName' => 'GroupLeader'],
            ['RoleID' => 3, 'RoleName' => 'Adviser'],
            ['RoleID' => 4, 'RoleName' => 'Faculty'],
            ['RoleID' => 5, 'RoleName' => 'Research Coordinator'],
            ['RoleID' => 6, 'RoleName' => 'Administrator'],
        ]);
    }
}
