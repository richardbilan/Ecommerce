<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('items');
            $table->enum('order_mode', ['delivery', 'pickup'])->default('pickup');
            $table->decimal('discount', 8, 2)->default(0); // Discount field
            $table->string('payment_method');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
