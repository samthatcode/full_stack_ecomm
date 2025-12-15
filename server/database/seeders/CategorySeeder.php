<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fruits & Vegetables', 'description' => 'Fresh organic fruits and vegetables'],
            ['name' => 'Meat & Seafood', 'description' => 'Premium quality meat and fresh seafood'],
            ['name' => 'Dairy & Eggs', 'description' => 'Fresh dairy products and farm eggs'],
            ['name' => 'Bakery', 'description' => 'Freshly baked bread, cakes, and pastries'],
            ['name' => 'Beverages', 'description' => 'Juices, soft drinks, and healthy beverages'],
            ['name' => 'Snacks', 'description' => 'Chips, nuts, and healthy snacks'],
            ['name' => 'Frozen Foods', 'description' => 'Frozen meals and ice cream'],
            ['name' => 'Pantry Staples', 'description' => 'Rice, pasta, oils, and spices'],
            ['name' => 'Health Foods', 'description' => 'Organic and health-focused products'],
            ['name' => 'Ready Meals', 'description' => 'Pre-cooked meals ready to eat'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => '/images/categories/' . Str::slug($category['name']) . '.jpg',
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
