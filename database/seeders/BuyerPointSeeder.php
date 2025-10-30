<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BuyerPoint;
use App\Models\User;

class BuyerPointSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = User::where('role', 'buyer')->get();

        foreach ($buyers as $buyer) {
            BuyerPoint::create([
                'buyer_id' => $buyer->id,
                'total_points' => 100.00, // Give each buyer 100 starting points
            ]);
        }
    }
}
