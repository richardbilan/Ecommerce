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
        Schema::table('user_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('user_orders', 'preparation_started_at')) {
                $table->timestamp('preparation_started_at')->nullable();
            }
            if (!Schema::hasColumn('user_orders', 'delivery_started_at')) {
                $table->timestamp('delivery_started_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn(['preparation_started_at', 'delivery_started_at']);
        });
    }
}; 