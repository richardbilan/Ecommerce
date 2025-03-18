<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the database with these seeders
        $this->call([
            CreateUsersSeeder::class,
            ProductsSeeder::class,
            PostSeeder::class,
        ]);

        // Optional factory example (commented out for now)
        /*
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */
    }
}
