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
        $jobOrderStatusTypes = ['New Request/Open', 'Under Review', 'Work in Progress', 'For Approval', 'Approved', 'Complete', 'Closed', 'On-hold', 'Cancelled', 'Void'];

        foreach ($jobOrderStatusTypes as $jobOrderStatusType) {
            $data = new JobOrderStatusType();
            $data->name = $jobOrderStatusType;
            $data->color = $this->generateRandomHexColor();
            $data->save();
        }
    }

    private function generateRandomHexColor() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}
