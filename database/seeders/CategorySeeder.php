<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fresh Produce',
                'slug' => 'fresh-produce',
                'icon' => 'fas fa-leaf',
                'color' => '#7CB342',
            ],
            [
                'name' => 'Animal Products',
                'slug' => 'animal-products',
                'icon' => 'fas fa-egg',
                'color' => '#FFA726',
            ],
            [
                'name' => 'Ingredient Bundle',
                'slug' => 'ingredient-bundle',
                'icon' => 'fas fa-box-open',
                'color' => '#EF5350',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
