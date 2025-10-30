<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SellerSeeder::class,
            RiderSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            BuyerPointSeeder::class,
        ]);
    }
}
