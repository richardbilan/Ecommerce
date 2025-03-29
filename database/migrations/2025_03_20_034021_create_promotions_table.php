<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code_name')->unique();
            $table->integer('discount');
            $table->date('expiration_date');
            $table->enum('status', ['Active', 'Expired'])->default('Active');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('promotions');
    }
};
