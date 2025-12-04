<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Note: The default User::factory() calls have been removed
        // because they rely on the 'name' attribute which no longer exists.
        // You will need to update your UserFactory to use 'first_name' and 'last_name'
        // if you wish to seed users automatically.
    }
}
