<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('g_cash_payments', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('checkout_url');
        });
    }

    public function down()
    {
        Schema::table('g_cash_payments', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
}; 