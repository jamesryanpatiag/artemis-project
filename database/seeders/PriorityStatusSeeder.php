<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PriorityStatus;

class PriorityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorityStatuses = ['Low', 'Medium', 'High', 'Urgent'];

        $data = new PriorityStatus();
        $data->name = 'Low';
        $data->color = "#b3ffb4";
        $data->save();

        $data = new PriorityStatus();
        $data->name = 'Medium';
        $data->color = "#feffb3";
        $data->save();

        $data = new PriorityStatus();
        $data->name = 'High';
        $data->color = "#ffdeb1";
        $data->save();

        $data = new PriorityStatus();
        $data->name = 'Urgent';
        $data->color = "#fc8281";
        $data->save();
    }
}
