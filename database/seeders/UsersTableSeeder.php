<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Seed for regular user
        $ic = '020407040150'; // Example IC
        $password = $ic; // Set password to IC

        DB::table('users')->updateOrInsert(
            ['username' => 'AI210151'],
            [
                'name' => 'Siti NurFalahah binti Mohd Ridoi',
                'password' => Hash::make($password),
                'IC' => $ic,
                'date_of_birth' => '2002-04-07', // Assuming the format is YYYY-MM-DD
                'gender' => 'female',
                'email' => 'ai210151@student.uthm.edu.my',
                'phone_number' => '0123476519',
                'role' => strpos('ai210151@student.uthm.edu.my', '@student.') !== false ? 'student' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Seed for admin user
        $adminIC = '010101010001';
        $adminPassword = $adminIC;

        DB::table('users')->updateOrInsert(
            ['username' => 'admin1'],
            [
                'name' => 'Admin 1',
                'password' => Hash::make($adminPassword),
                'IC' => $adminIC,
                'date_of_birth' => '2001-01-01', // Assuming the format is YYYY-MM-DD
                'gender' => 'male',
                'email' => 'admin1@uthm.edu.my',
                'phone_number' => '01100001111',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
