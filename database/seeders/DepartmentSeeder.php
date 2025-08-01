<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Sales', 'Purchasing', 'Production', 'Engineering'];

        foreach ($departments as $department) {
            $data = new Department();
            $data->name = $department;
            $data->save();
        }
    }
}
