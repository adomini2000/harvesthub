<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@harvesthub.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Create Sample Buyer
        User::create([
            'name' => 'John Buyer',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'is_approved' => true,
        ]);

        // Create Sample Seller (pending approval)
        $seller = User::create([
            'name' => 'Jane Seller',
            'email' => 'seller@test.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'is_approved' => false, // Needs approval
        ]);

        // Create seller profile
        $seller->seller()->create([
            'shop_name' => "Jane's Fresh Produce",
            'shop_description' => 'Quality vegetables and fruits',
            'subscription_paid' => false,
        ]);

        // Create Sample Rider (pending approval)
        $rider = User::create([
            'name' => 'Mike Rider',
            'email' => 'rider@test.com',
            'password' => Hash::make('password123'),
            'role' => 'rider',
            'is_approved' => false, // Needs approval
        ]);

        // Create rider profile
        $rider->rider()->create([
            'vehicle_type' => 'motorcycle',
            'max_capacity_kg' => 25.00,
            'status' => 'closed',
        ]);
    }
}
