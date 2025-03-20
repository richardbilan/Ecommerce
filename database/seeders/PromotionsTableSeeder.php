<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('promotions')->insert([
            [
                'code_name' => 'SUPEROHAH',
                'discount' => 10.00,
                'expiration_date' => '2025-01-02',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_name' => 'COFFEELOVER',
                'discount' => 15.00,
                'expiration_date' => '2025-05-01',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
