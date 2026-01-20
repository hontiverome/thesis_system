<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupAdvisersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('GroupAdvisers')->insert([
            [
                'GroupID' => 'G001',
                'AdviserUserID' => 113, // Prof. Tokyo Athena
            ],
            [
                'GroupID' => 'G002',
                'AdviserUserID' => 114, // Prof. Jose Rizal
            ],
            [
                'GroupID' => 'G003',
                'AdviserUserID' => 113, // Prof. Tokyo Athena
            ],
        ]);
    }
}
