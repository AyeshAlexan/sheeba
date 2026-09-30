<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_damage_sums', function (Blueprint $table) {
            $table->decimal('Total_qty', 15, 2)->default(0)->after('Store_code');
        });
    }

    public function down()
    {
        Schema::table('stock_damage_sums', function (Blueprint $table) {
            $table->dropColumn('Total_qty');
        });
    }
};
