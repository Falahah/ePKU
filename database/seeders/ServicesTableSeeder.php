<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch department IDs for each department
        $medicalDepartmentId = DB::table('departments')->where('name', 'Medical')->value('department_id');
        $medicalSupportDepartmentId = DB::table('departments')->where('name', 'Medical Support')->value('department_id');
        $dentalDepartmentId = DB::table('departments')->where('name', 'Dental')->value('department_id');

        // Define common opening and closing hours for multiple services
        $commonHours = [
            'sunday_opening' => '08:00',
            'sunday_closing' => '19:30',
            'monday_opening' => '08:00',
            'monday_closing' => '19:30',
            'tuesday_opening' => '08:00',
            'tuesday_closing' => '19:30',
            'wednesday_opening' => '08:00',
            'wednesday_closing' => '19:30',
            'thursday_opening' => '08:00',
            'thursday_closing' => '18:00',
        ];

        // Define services that share the same opening and closing hours
        $services = [
            'General Medical Treatment' => $medicalDepartmentId,
            'Medical Lab Services' => $medicalSupportDepartmentId,
            'Physiotherapy Services' => $medicalSupportDepartmentId,
            'X-ray Services' => $medicalSupportDepartmentId,
        ];

        // Insert services with common hours
        foreach ($services as $serviceType => $departmentId) {
            $serviceData = [
                'service_type' => $serviceType,
                'department_id' => $departmentId,
                'sunday_opening' => $commonHours['sunday_opening'],
                'sunday_closing' => $commonHours['sunday_closing'],
                'monday_opening' => $commonHours['monday_opening'],
                'monday_closing' => $commonHours['monday_closing'],
                'tuesday_opening' => $commonHours['tuesday_opening'],
                'tuesday_closing' => $commonHours['tuesday_closing'],
                'wednesday_opening' => $commonHours['wednesday_opening'],
                'wednesday_closing' => $commonHours['wednesday_closing'],
                'thursday_opening' => $commonHours['thursday_opening'],
                'thursday_closing' => $commonHours['thursday_closing'],
            ];

            DB::table('services')->insert($serviceData);
        }

        // Define specific opening and closing hours for the dental service
        $dentalService = [
            'service_type' => 'Dental Treatment',
            'department_id' => $dentalDepartmentId,
            'sunday_opening' => '08:00',
            'sunday_closing' => '17:00',
            'monday_opening' => '08:00',
            'monday_closing' => '17:00',
            'tuesday_opening' => '08:00',
            'tuesday_closing' => '17:00',
            'wednesday_opening' => '08:00',
            'wednesday_closing' => '17:00',
            'thursday_opening' => '08:00',
            'thursday_closing' => '15:30',
        ];

        // Insert the dental service with its specific hours
        DB::table('services')->insert($dentalService);
    }
}
