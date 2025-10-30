<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = Seller::all();
        $categories = Category::all();

        $products = [
            // Fresh Produce
            ['name' => 'Fresh Tomatoes', 'category' => 'fresh-produce', 'price' => 50.00, 'weight' => 1.0, 'stock' => 100],
            ['name' => 'Onions (1kg)', 'category' => 'fresh-produce', 'price' => 80.00, 'weight' => 1.0, 'stock' => 70],
            ['name' => 'Garlic (500g)', 'category' => 'fresh-produce', 'price' => 90.00, 'weight' => 0.5, 'stock' => 50],
            ['name' => 'Potatoes (2kg)', 'category' => 'fresh-produce', 'price' => 120.00, 'weight' => 2.0, 'stock' => 45],
            ['name' => 'Mangoes (1kg)', 'category' => 'fresh-produce', 'price' => 100.00, 'weight' => 1.0, 'stock' => 60],

            // Animal Products
            ['name' => 'Chicken Eggs (12pcs)', 'category' => 'animal-products', 'price' => 120.00, 'weight' => 0.8, 'stock' => 80],
            ['name' => 'Fresh Fish', 'category' => 'animal-products', 'price' => 180.00, 'weight' => 1.5, 'stock' => 30],
            ['name' => 'Fresh Milk (1L)', 'category' => 'animal-products', 'price' => 85.00, 'weight' => 1.1, 'stock' => 55],

            // Ingredient Bundle
            ['name' => 'Rice (5kg)', 'category' => 'ingredient-bundle', 'price' => 250.00, 'weight' => 5.0, 'stock' => 50],
            ['name' => 'Cooking Oil (1L)', 'category' => 'ingredient-bundle', 'price' => 150.00, 'weight' => 1.2, 'stock' => 40],
        ];

        foreach ($sellers as $seller) {
            foreach ($products as $productData) {
                $category = $categories->where('slug', $productData['category'])->first();

                if ($category) {
                    Product::create([
                        'seller_id' => $seller->id,
                        'category_id' => $category->id,
                        'name' => $productData['name'],
                        'description' => 'High quality ' . strtolower($productData['name']),
                        'price' => $productData['price'],
                        'stock' => $productData['stock'],
                        'weight_kg' => $productData['weight'],
                        'image_url' => null,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
