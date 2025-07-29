<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobOrderStatusType;


class JobOrderStatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobOrderStatusTypes = ['New Request/Open', 'Work in Progress', 'Complete', 'Closed', 'On-hold', 'Waiting for Approval', 'Cancelled', 'Void'];

        foreach ($jobOrderStatusTypes as $jobOrderStatusType) {
            $data = new JobOrderStatusType();
            $data->name = $jobOrderStatusType;
            $data->save();
        }
    }
}
