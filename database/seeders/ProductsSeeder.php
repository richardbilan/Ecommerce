<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Coffee Series
            [
                'product_name' => 'Long & Black',
                'category' => 'Coffee Series',
                'price_hot' => 95,
                'price_iced' => 95
            ],
            [
                'product_name' => 'Latte',
                'category' => 'Coffee Series',
                'price_hot' => 95,
                'price_iced' => 95
            ],
            [
                'product_name' => 'Viet-Style',
                'category' => 'Coffee Series',
                'price_hot' => 95,
                'price_iced' => 95
            ],
            [
                'product_name' => 'Hazelnut Latte',
                'category' => 'Coffee Series',
                'price_hot' => 99,
                'price_iced' => 99
            ],
            [
                'product_name' => 'Caramel Macchiato',
                'category' => 'Coffee Series',
                'price_hot' => 110,
                'price_iced' => 110
            ],
            [
                'product_name' => 'Black Magick',
                'category' => 'Coffee Series',
                'price_hot' => 110,
                'price_iced' => 110,
                'tag' => 'New'
            ],
            [
                'product_name' => 'Salted Caramel Macc',
                'category' => 'Coffee Series',
                'price_hot' => 115,
                'price_iced' => 115
            ],
            [
                'product_name' => 'Frosted Dark Mocha',
                'category' => 'Coffee Series',
                'price_hot' => 115,
                'price_iced' => 115,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'White Mocha',
                'category' => 'Coffee Series',
                'price_hot' => 115,
                'price_iced' => 115,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Espresso Matcha',
                'category' => 'Coffee Series',
                'price_hot' => 120,
                'price_iced' => 120,
                'tag' => 'New'
            ],
            [
                'product_name' => 'Timpladong Puti',
                'category' => 'Coffee Series',
                'price_hot' => 120,
                'price_iced' => null,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Java Lava',
                'category' => 'Coffee Series',
                'price_hot' => null,
                'price_iced' => 120
            ],

            // Non-Coffee Series
            [
                'product_name' => 'Dark Cacao',
                'category' => 'Non-Coffee Series',
                'price_hot' => 95,
                'price_iced' => 95
            ],
            [
                'product_name' => 'Nutty Cacao',
                'category' => 'Non-Coffee Series',
                'price_hot' => 99,
                'price_iced' => 99
            ],
            [
                'product_name' => 'Strawberry Cacao',
                'category' => 'Non-Coffee Series',
                'price_hot' => 99,
                'price_iced' => 99
            ],
            [
                'product_name' => 'Frosted Dark Cacao',
                'category' => 'Non-Coffee Series',
                'price_hot' => 110,
                'price_iced' => 110,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Salted Nutella Latte',
                'category' => 'Non-Coffee Series',
                'price_hot' => 110,
                'price_iced' => 110,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Matcha Latte',
                'category' => 'Non-Coffee Series',
                'price_hot' => 115,
                'price_iced' => 115
            ],
            [
                'product_name' => 'Frosted Matcha',
                'category' => 'Non-Coffee Series',
                'price_hot' => 120,
                'price_iced' => 120,
                'tag' => 'New'
            ],
            [
                'product_name' => 'Strawberry Matcha',
                'category' => 'Non-Coffee Series',
                'price_hot' => 120,
                'price_iced' => 120,
                'tag' => 'New'
            ],
            [
                'product_name' => 'Oreo Lava',
                'category' => 'Non-Coffee Series',
                'price_hot' => null,
                'price_iced' => 120,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Cacao Lava',
                'category' => 'Non-Coffee Series',
                'price_hot' => null,
                'price_iced' => 120,
                'tag' => 'Best Seller'
            ],
            [
                'product_name' => 'Strawberry Lava',
                'category' => 'Non-Coffee Series',
                'price_hot' => null,
                'price_iced' => 120
            ],
            [
                'product_name' => 'S’mores Lava',
                'category' => 'Non-Coffee Series',
                'price_hot' => null,
                'price_iced' => 120,
                'tag' => 'New'
            ],
        ];

        // Loop through each product and insert into the database
        foreach ($products as $index => $product) {

            Products::create([
                'product_id' => 'PROD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'product_name' => $product['product_name'],
                'category' => $product['category'],
                'price_hot' => $product['price_hot'],
                'price_iced' => $product['price_iced'],
                'availability' => $product['availability'] ?? 'In Stock',
                'tag' => $product['tag'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
