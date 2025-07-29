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
            UserRoleSeeder::class,
            JobOrderStatusTypeSeeder::class
        ]);
        
        User::create([
            'name' => 'admin',
            'email' => 'admin@artemis.com',
            'email_verified_at' => now(),
            'user_role_id' => 1,
            'password' => bcrypt('!Password@123'),
        ]);

        
    }
}
