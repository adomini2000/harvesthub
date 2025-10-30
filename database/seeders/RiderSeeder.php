<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rider;
use App\Models\User;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        $riders = User::where('role', 'rider')->get();

        $vehicleTypes = ['bike', 'motorcycle', 'car'];
        $capacities = [10.00, 25.00, 50.00];

        foreach ($riders as $index => $user) {
            Rider::create([
                'user_id' => $user->id,
                'vehicle_type' => $vehicleTypes[$index % 3],
                'max_capacity_kg' => $capacities[$index % 3],
                'status' => 'normal',
            ]);
        }
    }
}
