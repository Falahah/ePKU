<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'Medical',
            'Medical Support',
            'Dental',
            'Administration & Finance'
        ];

        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }
    }
}
