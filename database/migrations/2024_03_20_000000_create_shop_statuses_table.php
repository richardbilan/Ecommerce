<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(false);
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamp('last_closed_at')->nullable();
            $table->foreignId('current_location_id')->nullable()->constrained('cafe_locations')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_statuses');
    }
}; 