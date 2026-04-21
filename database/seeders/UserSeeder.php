<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@crispac.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        
        // Create customer user
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}