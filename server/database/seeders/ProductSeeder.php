<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categories = Category::all();

        $productTemplates = [
            // Fruits & Vegetables
            ['Organic Banana', 'Fresh organic bananas rich in potassium'],
            ['Red Apple', 'Crisp and sweet red apples'],
            ['Green Lettuce', 'Fresh crispy lettuce for salads'],
            ['Tomatoes', 'Ripe juicy tomatoes'],
            ['Carrots', 'Fresh crunchy carrots'],
            ['Avocado', 'Creamy ripe avocados'],
            ['Strawberries', 'Sweet fresh strawberries'],
            ['Oranges', 'Juicy fresh oranges'],
            ['Grapes', 'Seedless sweet grapes'],
            ['Cucumber', 'Fresh crisp cucumber'],

            // Meat & Seafood
            ['Chicken Breast', 'Lean protein-rich chicken breast'],
            ['Beef Steak', 'Premium quality beef steak'],
            ['Fresh Salmon', 'Wild-caught Atlantic salmon'],
            ['Ground Beef', 'Lean ground beef'],
            ['Pork Chops', 'Tender pork chops'],
            ['Shrimp', 'Fresh jumbo shrimp'],
            ['Lamb Chops', 'Premium lamb chops'],
            ['Turkey Breast', 'Lean turkey breast'],
            ['Tuna', 'Fresh tuna fillet'],
            ['Chicken Wings', 'Fresh chicken wings'],

            // Dairy & Eggs
            ['Whole Milk', 'Fresh whole milk'],
            ['Greek Yogurt', 'Creamy Greek yogurt'],
            ['Cheddar Cheese', 'Aged cheddar cheese'],
            ['Butter', 'Pure dairy butter'],
            ['Free-Range Eggs', 'Farm fresh eggs'],
            ['Mozzarella Cheese', 'Fresh mozzarella'],
            ['Cream Cheese', 'Smooth cream cheese'],
            ['Sour Cream', 'Fresh sour cream'],
            ['Cottage Cheese', 'Low-fat cottage cheese'],
            ['Almond Milk', 'Unsweetened almond milk'],

            // Bakery
            ['Whole Wheat Bread', 'Freshly baked whole wheat bread'],
            ['Croissant', 'Buttery flaky croissants'],
            ['Bagels', 'Fresh baked bagels'],
            ['Sourdough Bread', 'Artisan sourdough bread'],
            ['Cinnamon Rolls', 'Sweet cinnamon rolls'],
            ['Blueberry Muffins', 'Fresh blueberry muffins'],
            ['Chocolate Cake', 'Rich chocolate cake'],
            ['Cookies', 'Freshly baked cookies'],
            ['Donuts', 'Glazed donuts'],
            ['Baguette', 'Crispy French baguette'],

            // Generic templates for bulk generation
            ['Premium Product', 'High quality product'],
            ['Organic Product', 'Certified organic product'],
            ['Fresh Product', 'Farm fresh product'],
            ['Gourmet Product', 'Gourmet quality product'],
            ['Natural Product', 'All natural product'],
        ];

        $ingredients = [
            ['Water', 'Wheat', 'Salt', 'Sugar'],
            ['Milk', 'Eggs', 'Flour', 'Butter'],
            ['Tomatoes', 'Onions', 'Garlic', 'Herbs'],
            ['Rice', 'Beans', 'Spices', 'Oil'],
            ['Fruits', 'Vegetables', 'Nuts', 'Seeds'],
        ];

        $allergens = [
            ['Gluten', 'Dairy'],
            ['Nuts', 'Soy'],
            ['Eggs', 'Milk'],
            ['Fish', 'Shellfish'],
            ['Wheat', 'Peanuts'],
        ];

        $count = 0;

        foreach ($categories as $category) {
            $productsPerCategory = ceil(500 / $categories->count());

            for ($i = 0; $i < $productsPerCategory && $count < 500; $i++) {
                $template = $faker->randomElement($productTemplates);
                $suffix = $i > count($productTemplates) ? " {$faker->word()}" : "";
                $name = $template[0] . $suffix . " " . $faker->numberBetween(1, 999);
                $slug = Str::slug($name . '-' . $count);

                $price = $faker->randomFloat(2, 5, 200);
                $hasSale = $faker->boolean(30);
                $salePrice = $hasSale ? $price * $faker->randomFloat(2, 0.7, 0.9) : null;

                Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $faker->paragraph(3),
                    'short_description' => $template[1] ?? $faker->sentence(),
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock_quantity' => $faker->numberBetween(0, 500),
                    'sku' => 'SKU-' . strtoupper($faker->bothify('???-####')),
                    'image' => '/images/products/product-' . ($count + 1) . '.jpg',
                    'images' => json_encode([
                        '/images/products/product-' . ($count + 1) . '-1.jpg',
                        '/images/products/product-' . ($count + 1) . '-2.jpg',
                        '/images/products/product-' . ($count + 1) . '-3.jpg',
                    ]),
                    'is_featured' => $faker->boolean(10),
                    'is_active' => $faker->boolean(95),
                    'views_count' => $faker->numberBetween(0, 1000),
                    'rating' => $faker->randomFloat(2, 3.5, 5.0),
                    'reviews_count' => $faker->numberBetween(0, 200),
                    'weight' => $faker->numberBetween(100, 2000) . 'g',
                    'calories' => $faker->numberBetween(50, 500) . ' kcal',
                    'protein' => $faker->numberBetween(1, 50) . 'g',
                    'carbs' => $faker->numberBetween(5, 100) . 'g',
                    'fat' => $faker->numberBetween(1, 30) . 'g',
                    'fiber' => $faker->numberBetween(1, 15) . 'g',
                    'ingredients' => json_encode($faker->randomElement($ingredients)),
                    'allergens' => json_encode($faker->randomElement($allergens)),
                ]);

                $count++;
            }
        }

        $this->command->info("Created {$count} products successfully!");
    }
}
