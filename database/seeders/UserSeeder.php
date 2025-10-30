<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Buyers
        User::create([
            'name' => 'John Buyer',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        User::create([
            'name' => 'Jane Buyer',
            'email' => 'buyer2@test.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        // Create Sellers
        User::create([
            'name' => 'Maria Seller',
            'email' => 'seller@test.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        User::create([
            'name' => 'Carlos Seller',
            'email' => 'seller2@test.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        // Create Riders
        User::create([
            'name' => 'Pedro Rider',
            'email' => 'rider@test.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
        ]);

        User::create([
            'name' => 'Miguel Rider',
            'email' => 'rider2@test.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
        ]);
    }
}
