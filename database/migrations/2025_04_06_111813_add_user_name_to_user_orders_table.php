<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('user_orders', function (Blueprint $table) {
        if (!Schema::hasColumn('user_orders', 'user_name')) {
            $table->string('user_name')->after('user_id'); // Or wherever you prefer
        }
    });
}

public function down()
{
    Schema::table('user_orders', function (Blueprint $table) {
        $table->dropColumn('user_name');
    });
}

};
