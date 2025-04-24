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
            $table->dropColumn(['tracking_number', 'scheduled_time', 'special_instructions', 'tax_amount']);
            $table->string('shop_address')->nullable(); // Add shop address
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->string('tracking_number')->unique()->nullable();
            $table->timestamp('scheduled_time')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->dropColumn('shop_address');
        });
    }
};
