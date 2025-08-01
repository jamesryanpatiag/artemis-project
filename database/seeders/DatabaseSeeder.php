<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JobOrderStatusTypeSeeder::class,
            DepartmentSeeder::class
        ]);
        
        User::create([
            'name' => 'admin',
            'email' => 'admin@artemis.com',
            'email_verified_at' => now(),
            'password' => bcrypt('!Password@123'),
        ]);

        
    }
}
