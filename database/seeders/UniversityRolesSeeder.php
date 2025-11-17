<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Support\Carbon; // Import Carbon for now()

class UniversityRolesSeeder extends Seeder
{
    /**
     * រត់ Database Seeds.
     */
    public function run(): void
    {
        // បង្កើត Admin User
        User::create([
            'name' => 'University Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin1234!@#$'), // Password គឺ 'password'
            'role' => 'admin',
            'email_verified_at' => Carbon::now(), // Verify email immediately for testing
        ]);

        // បង្កើត Professor User
        User::create([
            'name' => 'Professor John Doe',
            'email' => 'professor@gmail.com',
            'password' => Hash::make('Professor1234!@#$'), // Password គឺ 'password'
            'role' => 'professor',
            'email_verified_at' => Carbon::now(),
        ]);

        
    }
}
