<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('user_orders', function (Blueprint $table) {
        $table->id();
        $table->json('items'); // Stores items as JSON
        $table->string('payment_method');
        $table->decimal('total_amount', 10, 2);
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('user_name'); // 👈 Add this
        $table->string('name');
        $table->decimal('price', 10, 2);
        $table->integer('quantity');
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
