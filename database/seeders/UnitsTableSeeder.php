<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    public function run()
    {
        // Retrieve the department IDs for Medical Department and Management Department
        $medicalDepartmentId = DB::table('departments')->where('name', 'Medical')->value('department_id');
        $medicalSupportDepartmentId = DB::table('departments')->where('name', 'Medical Support')->value('department_id');
        $administrationFinanceDepartmentId = DB::table('departments')->where('name', 'Administration & Finance')->value('department_id');
        $physiotherapyServicesId = DB::table('services')->where('service_type', 'Physiotherapy Services')->value('service_id');
        $medicalLabServicesId = DB::table('services')->where('service_type', 'Medical Lab Services')->value('service_id');
        $xRayServicesId = DB::table('services')->where('service_type', 'X-ray Services')->value('service_id');
        $pharmacyServicesId = DB::table('services')->where('service_type', 'Pharmacy Services')->value('service_id');

        // Define units with their associated department IDs and service IDs
        $units = [
            ['name' => 'Outpatient Unit', 'department_id' => $medicalDepartmentId],
            ['name' => 'Nursing Unit', 'department_id' => $medicalDepartmentId],
            ['name' => 'Pharmacy Unit', 'department_id' => $medicalSupportDepartmentId, 'service_id' => $pharmacyServicesId],
            ['name' => 'Environmental Health Unit', 'department_id' => $medicalSupportDepartmentId],
            ['name' => 'Medical Rehabilitation Unit', 'department_id' => $medicalSupportDepartmentId, 'service_id' => $physiotherapyServicesId],
            ['name' => 'Medical Laboratory Unit', 'department_id' => $medicalSupportDepartmentId, 'service_id' => $medicalLabServicesId],
            ['name' => 'Imaging Unit', 'department_id' => $medicalSupportDepartmentId, 'service_id' => $xRayServicesId],
            ['name' => 'Information & Technology Unit', 'department_id' => $administrationFinanceDepartmentId],
            ['name' => 'Financial Unit', 'department_id' => $administrationFinanceDepartmentId],
            ['name' => 'Administrative Unit', 'department_id' => $administrationFinanceDepartmentId],
        ];

        // Insert units into the database
        foreach ($units as $unitData) {
            Unit::create($unitData);
        }

        // Update the departments table with the corresponding unit_id
        $units = Unit::all();
        foreach ($units as $unit) {
            DB::table('departments')
                ->where('department_id', $unit->department_id)
                ->update(['unit' => $unit->unit_id]);
        }
    }
}
