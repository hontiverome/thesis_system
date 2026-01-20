<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Groups')->insert([
            // Group 1 - Adviser: Prof. Tokyo Athena (UserID 113)
            [
                'GroupID' => 'G001',
                'GroupCode' => 'THESIS-2024-G01',
                'YearLevel' => 4,
                'AdviserUserID' => 113,
            ],
            // Group 2 - Adviser: Prof. Jose Rizal (UserID 114)
            [
                'GroupID' => 'G002',
                'GroupCode' => 'THESIS-2024-G02',
                'YearLevel' => 4,
                'AdviserUserID' => 114,
            ],
            // Group 3 - Adviser: Prof. Tokyo Athena (UserID 113)
            [
                'GroupID' => 'G003',
                'GroupCode' => 'THESIS-2024-G03',
                'YearLevel' => 4,
                'AdviserUserID' => 113,
            ],
        ]);
    }
}
