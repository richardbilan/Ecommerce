<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop existing tables if they exist
        Schema::dropIfExists('shop_statuses');
        Schema::dropIfExists('shop_status');

        Schema::create('shop_status', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(false);
            $table->timestamps();
        });

        // Insert the initial record
        DB::table('shop_status')->insert([
            'is_open' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('shop_status');
    }
}; 