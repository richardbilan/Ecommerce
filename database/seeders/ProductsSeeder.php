<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Coffee Series
            ['product_name' => 'Long & Black', 'category' => 'Coffee Series', 'price' => 95, 'availability' => 'In Stock'],
            ['product_name' => 'Latte', 'category' => 'Coffee Series', 'price' => 95, 'availability' => 'In Stock'],
            ['product_name' => 'Viet-Style', 'category' => 'Coffee Series', 'price' => 95, 'availability' => 'In Stock'],
            ['product_name' => 'Hazelnut Latte', 'category' => 'Coffee Series', 'price' => 99, 'availability' => 'In Stock'],
            ['product_name' => 'Caramel Macchiato', 'category' => 'Coffee Series', 'price' => 110, 'availability' => 'In Stock'],
            ['product_name' => 'Black Magick', 'category' => 'Coffee Series', 'price' => 110, 'availability' => 'In Stock', 'tag' => 'New'],
            ['product_name' => 'Salted Caramel Macc', 'category' => 'Coffee Series', 'price' => 115, 'availability' => 'In Stock'],
            ['product_name' => 'Frosted Dark Mocha', 'category' => 'Coffee Series', 'price' => 115, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'White Mocha', 'category' => 'Coffee Series', 'price' => 115, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Espresso Matcha', 'category' => 'Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'New'],
            ['product_name' => 'Timpladong Puti', 'category' => 'Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Java Lava', 'category' => 'Coffee Series', 'price' => 120, 'availability' => 'In Stock'],

            // Non-Coffee Series
            ['product_name' => 'Dark Cacao', 'category' => 'Non-Coffee Series', 'price' => 95, 'availability' => 'In Stock'],
            ['product_name' => 'Nutty Cacao', 'category' => 'Non-Coffee Series', 'price' => 99, 'availability' => 'In Stock'],
            ['product_name' => 'Strawberry Cacao', 'category' => 'Non-Coffee Series', 'price' => 99, 'availability' => 'In Stock'],
            ['product_name' => 'Frosted Dark Cacao', 'category' => 'Non-Coffee Series', 'price' => 110, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Salted Nutella Latte', 'category' => 'Non-Coffee Series', 'price' => 110, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Matcha Latte', 'category' => 'Non-Coffee Series', 'price' => 115, 'availability' => 'In Stock'],
            ['product_name' => 'Frosted Matcha', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'New'],
            ['product_name' => 'Strawberry Matcha', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'New'],
            ['product_name' => 'Oreo Lava', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Cacao Lava', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'Best Seller'],
            ['product_name' => 'Strawberry Lava', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock'],
            ['product_name' => 'S’mores Lava', 'category' => 'Non-Coffee Series', 'price' => 120, 'availability' => 'In Stock', 'tag' => 'New'],
        ];

        foreach ($products as $index => $product) {
            Products::create([
                'product_name' => $product['product_name'],
                'category' => $product['category'],
                'price' => $product['price'],
                'product_id' => 'PROD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'availability' => $product['availability'],
                // Uncomment this if your table has a "tag" column
                'tag' => $product['tag'] ?? null,
            ]);
        }
    }
}
