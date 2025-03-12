<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Products::all();
        $users = User::all();

        for ($i = 0; $i < 20; $i++) {
            Post::create([
                'title' => "post $i",
                'description' => "description $i",
                'product_id' => $product->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
