<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique();
            $table->string('product_name');
            $table->string('category');
            $table->decimal('price_hot', 8, 2)->nullable();
            $table->decimal('price_iced', 8, 2)->nullable();
            $table->string('availability');
            $table->string('tag')->nullable(); // This exists now!
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
