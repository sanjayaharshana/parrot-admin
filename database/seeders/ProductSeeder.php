<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera system and A17 Pro chip',
                'category_id' => 1,
                'price' => 999.99,
                'stock_quantity' => 50,
                'sku' => 'IPH15PRO-001',
                'status' => 'active'
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Ultra-thin laptop with M2 chip and all-day battery life',
                'category_id' => 1,
                'price' => 1199.99,
                'stock_quantity' => 25,
                'sku' => 'MBA-M2-001',
                'status' => 'active'
            ],
            [
                'name' => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes with Air Max technology',
                'category_id' => 2,
                'price' => 150.00,
                'stock_quantity' => 100,
                'sku' => 'NIKE-AM270-001',
                'status' => 'active'
            ],
            [
                'name' => 'The Art of Programming',
                'description' => 'Comprehensive guide to modern programming practices',
                'category_id' => 3,
                'price' => 49.99,
                'stock_quantity' => 75,
                'sku' => 'BOOK-PROG-001',
                'status' => 'active'
            ],
            [
                'name' => 'Garden Tool Set',
                'description' => 'Complete set of essential gardening tools',
                'category_id' => 4,
                'price' => 89.99,
                'stock_quantity' => 30,
                'sku' => 'GARDEN-TOOLS-001',
                'status' => 'active'
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Android flagship with AI features and excellent camera',
                'category_id' => 1,
                'price' => 899.99,
                'stock_quantity' => 0,
                'sku' => 'SAMSUNG-S24-001',
                'status' => 'inactive'
            ],
            [
                'name' => 'Designer T-Shirt',
                'description' => 'Premium cotton t-shirt with unique design',
                'category_id' => 2,
                'price' => 45.00,
                'stock_quantity' => 200,
                'sku' => 'TSHIRT-DESIGN-001',
                'status' => 'draft'
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']], // Check if product with this SKU exists
                $product // Update or create with this data
            );
        }
    }
} 