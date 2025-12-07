<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
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
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'student'],
            ['name' => 'faculty'],
            ['name' => 'research-coordinator'],
            ['name' => 'admin'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}