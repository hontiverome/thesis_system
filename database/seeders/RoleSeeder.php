<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to truncate the table.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table to start from a clean state.
        Role::truncate();

        // Re-enable foreign key checks.
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = [
            ['name' => 'Student'],
            ['name' => 'Faculty'],
            ['name' => 'Research Coordinator'],
            ['name' => 'Admin'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}