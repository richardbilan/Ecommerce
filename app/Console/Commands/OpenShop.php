<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CafeLocation;

class OpenShop extends Command
{
    protected $signature = 'shop:open';
    protected $description = 'Open the shop at the current location';

    public function handle()
    {
        try {
            $location = CafeLocation::getCurrentLocation();
            
            if (!$location) {
                $this->error('No current location set');
                return 1;
            }

            $location->update([
                'is_open' => true,
                'last_opened_at' => now()
            ]);

            \Cache::forget('cafe_location_status');
            \Cache::put('cafe_location_status', true, now()->addHours(24));

            $this->info('Shop opened successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error opening shop: ' . $e->getMessage());
            return 1;
        }
    }
} 