<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_orders', function (Blueprint $table) {
            // Modify the ENUM to match valid statuses
            $table->enum('status', [
                'pending', 'processing', 'preparing', 'delivering', 'delivered', 'cancelled'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_orders', function (Blueprint $table) {
            // Rollback to the previous ENUM definition
            $table->enum('status', [
                'pending', 'processing', 'completed', 'cancelled'
            ])->default('pending')->change();
        });
    }
};
