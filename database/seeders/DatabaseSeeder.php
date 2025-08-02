<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PriorityStatusSeeder::class,
            JobOrderStatusTypeSeeder::class,
            DepartmentSeeder::class
        ]);
        
        User::create([
            'name' => 'admin',
            'email' => 'admin@artemis.com',
            'email_verified_at' => now(),
            'password' => bcrypt('!Password@123'),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\\Models\\User',
            'model_id' => 1
        ]);
        
    }
}
