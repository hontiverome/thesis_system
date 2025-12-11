<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Users')->insert([
            ['UserID' => 101, 'SchoolID' => '2022-01001-MN-0', 'FullName' => 'Leader G1', 'Email' => 's1@gmail.com', 'BirthDate' => '2003-01-01', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 102, 'SchoolID' => '2022-01002-MN-0', 'FullName' => 'Member G1-A', 'Email' => 's2@gmail.com', 'BirthDate' => '2003-01-02', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 103, 'SchoolID' => '2022-01003-MN-0', 'FullName' => 'Member G1-B', 'Email' => 's3@gmail.com', 'BirthDate' => '2003-01-03', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 104, 'SchoolID' => '2022-01004-MN-0', 'FullName' => 'Member G1-C', 'Email' => 's4@gmail.com', 'BirthDate' => '2003-01-04', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 105, 'SchoolID' => '2022-02001-MN-0', 'FullName' => 'Leader G2', 'Email' => 's5@gmail.com', 'BirthDate' => '2003-02-01', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 106, 'SchoolID' => '2022-02002-MN-0', 'FullName' => 'Member G2-A', 'Email' => 's6@gmail.com', 'BirthDate' => '2003-02-02', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 107, 'SchoolID' => '2022-02003-MN-0', 'FullName' => 'Member G2-B', 'Email' => 's7@gmail.com', 'BirthDate' => '2003-02-03', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 108, 'SchoolID' => '2022-02004-MN-0', 'FullName' => 'Member G2-C', 'Email' => 's8@gmail.com', 'BirthDate' => '2003-02-04', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 109, 'SchoolID' => '2022-03001-MN-0', 'FullName' => 'Leader G3', 'Email' => 's9@gmail.com', 'BirthDate' => '2003-03-01', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 110, 'SchoolID' => '2022-03002-MN-0', 'FullName' => 'Member G3-A', 'Email' => 's10@gmail.com', 'BirthDate' => '2003-03-02', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 111, 'SchoolID' => '2022-03003-MN-0', 'FullName' => 'Member G3-B', 'Email' => 's11@gmail.com', 'BirthDate' => '2003-03-03', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 112, 'SchoolID' => '2022-03004-MN-0', 'FullName' => 'Member G3-C', 'Email' => 's12@gmail.com', 'BirthDate' => '2003-03-04', 'PasswordHash' => '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'],
            ['UserID' => 113, 'SchoolID' => '2010-00001-FA-0', 'FullName' => 'Prof. Tokyo Athena', 'Email' => 'tokyo@gmail.com', 'BirthDate' => null, 'PasswordHash' => '$2y$10$dummyhash'],
            ['UserID' => 114, 'SchoolID' => '2010-00002-FA-0', 'FullName' => 'Prof. Jose Rizal', 'Email' => 'jose@gmail.com', 'BirthDate' => null, 'PasswordHash' => '$2y$10$dummyhash'],
            ['UserID' => 115, 'SchoolID' => '2010-00003-FA-0', 'FullName' => 'Prof. Clara Oswald', 'Email' => 'clara@gmail.com', 'BirthDate' => null, 'PasswordHash' => '$2y$10$dummyhash'],
            ['UserID' => 116, 'SchoolID' => '2010-00004-FA-0', 'FullName' => 'Prof. Sherlock', 'Email' => 'sherlock@gmail.com', 'BirthDate' => null, 'PasswordHash' => '$2y$10$dummyhash'],
            ['UserID' => 117, 'SchoolID' => '2000-00000-AD-0', 'FullName' => 'Admin User', 'Email' => 'admin@gmail.com', 'BirthDate' => null, 'PasswordHash' => '$2y$10$dummyhash'],
        ]);
    }
}
