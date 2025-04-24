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
        Schema::create('user_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('items');
            $table->string('tracking_number')->unique()->nullable();
            $table->timestamp('scheduled_time')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('delivery_address')->nullable();
            $table->string('contact_number')->nullable();
            $table->enum('order_mode', ['delivery', 'pickup'])->default('pickup');
            $table->decimal('discount', 8, 2)->default(0);
            $table->string('payment_method');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('preparation_started_at')->nullable();
            $table->timestamp('preparation_completed_at')->nullable();
            $table->timestamp('delivery_started_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_orders');
    }
};
