<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $categories = [
            ['name' => 'Korean Food', 'image' => 'https://images.unsplash.com/photo-1547928576-a4a33237cea4?w=500&auto=format&fit=crop'],
            ['name' => 'Indonesian Food', 'image' => 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=500&auto=format&fit=crop'],
            ['name' => 'Chinese Food', 'image' => 'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=500&auto=format&fit=crop'],
            ['name' => 'Indian Food', 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&auto=format&fit=crop'],
            ['name' => 'Middle Eastern Food', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=500&auto=format&fit=crop'],
            ['name' => 'Fast Food', 'image' => 'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=500&auto=format&fit=crop'],
        ];

        foreach ($categories as $cat) {
            $category = Category::create($cat);

            // Create products for each category
            if ($cat['name'] == 'Indonesian Food') {
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Nasi Goreng Special',
                    'description' => 'Indonesian fried rice with fried egg and crackers.',
                    'price' => 3.99,
                    'is_popular' => true,
                    'image' => 'https://images.unsplash.com/photo-1623653387945-2fd25214f8fc?w=500&auto=format&fit=crop'
                ]);
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Sate Ayam',
                    'description' => 'Chicken skewers with delicious peanut sauce.',
                    'price' => 3.99,
                    'is_popular' => false,
                    'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500&auto=format&fit=crop'
                ]);
            } elseif ($cat['name'] == 'Korean Food') {
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Kimchi Stew',
                    'description' => 'Spicy and sour stew with tofu and vegetables.',
                    'price' => 3.99,
                    'is_popular' => true,
                    'image' => 'https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?w=500&auto=format&fit=crop'
                ]);
                Product::create([
                    'category_id' => $category->id,
                    'name' => 'Bibimbap',
                    'description' => 'Rice mixed with vegetables, beef, and spicy sauce.',
                    'price' => 3.99,
                    'is_popular' => true,
                    'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop'
                ]);
            } else {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $cat['name'] . ' Deluxe',
                    'description' => 'Premium dish from our ' . $cat['name'] . ' selection.',
                    'price' => 3.99,
                    'is_popular' => false,
                    'image' => 'https://images.unsplash.com/photo-1512058560566-d8d4c724f261?w=500&auto=format&fit=crop'
                ]);
                Product::create([
                    'category_id' => $category->id,
                    'name' => $cat['name'] . ' Standard',
                    'description' => 'Casual dining from our ' . $cat['name'] . ' selection.',
                    'price' => 3.99,
                    'is_popular' => false,
                    'image' => 'https://images.unsplash.com/photo-1512058560566-d8d4c724f261?w=500&auto=format&fit=crop'
                ]);
            }
        }
    }
}
