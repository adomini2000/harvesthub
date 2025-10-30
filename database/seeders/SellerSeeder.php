<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seller;
use App\Models\User;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')->get();

        foreach ($sellers as $index => $user) {
            Seller::create([
                'user_id' => $user->id,
                'shop_name' => 'Shop ' . ($index + 1),
                'shop_description' => 'Quality products for everyone',
                'subscription_paid' => true,
                'rating' => 4.5,
                'total_ratings' => 10,
            ]);
        }
    }
}
