<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*Optional: Factory generated user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */

        // Seed the database with the following seeders
        $this->call([
            CreateUsersSeeder::class,
            ProductsSeeder::class,
            PostSeeder::class
        ]);
    }
}
