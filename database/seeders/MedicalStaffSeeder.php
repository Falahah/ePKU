<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalStaff;

class MedicalStaffSeeder extends Seeder
{
    public function run()
    {
        // Define sample medical staff data
        $medicalStaffData = [
            [
                'name' => 'Ahmad Bin Abdullah',
                'gender' => 'Male',
                'email' => 'ahmad@uthm.edu.my',
                'phone_number' => '0123456789',
                'position' => 'Doctor',
                'department_id' => 1, // Medical Department ID
                'active' => true, // Add active field
            ],
            [
                'name' => 'Siti Binti Ahmad',
                'gender' => 'Female',
                'email' => 'siti@uthm.edu.my',
                'phone_number' => '0134567890',
                'position' => 'Nurse',
                'department_id' => 1, // Medical Department ID
                'unit_id' => 2,
                'active' => true, // Add active field
            ],
            [
                'name' => 'Muhammad Bin Ali',
                'gender' => 'Male',
                'email' => 'muhammad@uthm.edu.my',
                'phone_number' => '0145678901',
                'position' => 'Medical Lab Assistant',
                'department_id' => 2, // Dental Department ID
                'unit_id' => 6,
                'active' => false, // Add active field
            ],
            [
                'name' => 'Zainab Binti Mohamed',
                'gender' => 'Female',
                'email' => 'zainab@uthm.edu.my',
                'phone_number' => '0156789012',
                'position' => 'Nurse',
                'department_id' => 1, // Medical Department ID
                'unit_id' => 2,
                'active' => true, // Add active field
            ],
            [
                'name' => 'Hassan Bin Ibrahim',
                'gender' => 'Male',
                'email' => 'hassan@uthm.edu.my',
                'phone_number' => '0167890123',
                'position' => 'Dentist',
                'department_id' => 3,
                'active' => false, // Add active field
            ],
            // Add more entries as needed
        ];

        // Insert medical staff data into the database
        foreach ($medicalStaffData as $staff) {
            MedicalStaff::create($staff);
        }
    }
}
