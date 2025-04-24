<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('g_cash_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('user_orders')->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('checkout_url');
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('g_cash_payments');
    }
}; 