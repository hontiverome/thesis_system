<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Truncate the table to start from a clean state.
        Role::truncate();

        // Re-enable foreign key checks.
        Schema::enableForeignKeyConstraints();

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