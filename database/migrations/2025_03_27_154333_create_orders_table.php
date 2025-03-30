<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // If user is logged in
            $table->json('products'); // Stores product details as JSON
            $table->integer('quantity');
            $table->string('category');
            $table->enum('temperature', ['hot', 'cold']);
            $table->string('promo_code')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->enum('order_type', ['pickup', 'delivery']);
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->decimal('promo_discount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['gcash', 'cash']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
