<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'first_name' => 'test',
            'last_name' => 'test',
            'slug' => 'test',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),  // Make sure to hash passwords
            'phone' => '1234567890',
            'birth_date' => '2000-01-01',
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Assign the super admin role to this user if roles are being managed
        $user->assignRole('super_admin');
    }
}
