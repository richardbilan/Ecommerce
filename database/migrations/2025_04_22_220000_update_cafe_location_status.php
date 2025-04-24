<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CafeLocation;

return new class extends Migration
{
    public function up()
    {
        // Update the default cafe location to be open
        $location = CafeLocation::first();
        if ($location) {
            $location->update([
                'is_open' => true,
                'last_opened_at' => now()
            ]);
        }
    }

    public function down()
    {
        // Revert the cafe location to closed
        $location = CafeLocation::first();
        if ($location) {
            $location->update([
                'is_open' => false,
                'last_closed_at' => now()
            ]);
        }
    }
}; 