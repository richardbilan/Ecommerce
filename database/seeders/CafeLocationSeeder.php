<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CafeLocation;

class CafeLocationSeeder extends Seeder
{
    public function run()
    {
        CafeLocation::create([
            'address' => 'Default Location',
            'latitude' => 0,
            'longitude' => 0,
            'description' => 'Default cafe location',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'is_current' => true,
            'is_open' => true,
            'last_opened_at' => now()
        ]);
    }
} 