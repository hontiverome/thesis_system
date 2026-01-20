<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('GroupMembers')->insert([
            // Group 1 Members (Students 101-104)
            [
                'GroupID' => 'G001',
                'StudentUserID' => 101, // Leader G1
                'GroupRole' => 'GroupLeader',
            ],
            [
                'GroupID' => 'G001',
                'StudentUserID' => 102, // Member G1-A
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G001',
                'StudentUserID' => 103, // Member G1-B
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G001',
                'StudentUserID' => 104, // Member G1-C
                'GroupRole' => 'GroupMember',
            ],

            // Group 2 Members (Students 105-108)
            [
                'GroupID' => 'G002',
                'StudentUserID' => 105, // Leader G2
                'GroupRole' => 'GroupLeader',
            ],
            [
                'GroupID' => 'G002',
                'StudentUserID' => 106, // Member G2-A
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G002',
                'StudentUserID' => 107, // Member G2-B
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G002',
                'StudentUserID' => 108, // Member G2-C
                'GroupRole' => 'GroupMember',
            ],

            // Group 3 Members (Students 109-112)
            [
                'GroupID' => 'G003',
                'StudentUserID' => 109, // Leader G3
                'GroupRole' => 'GroupLeader',
            ],
            [
                'GroupID' => 'G003',
                'StudentUserID' => 110, // Member G3-A
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G003',
                'StudentUserID' => 111, // Member G3-B
                'GroupRole' => 'GroupMember',
            ],
            [
                'GroupID' => 'G003',
                'StudentUserID' => 112, // Member G3-C
                'GroupRole' => 'GroupMember',
            ],
        ]);
    }
}
