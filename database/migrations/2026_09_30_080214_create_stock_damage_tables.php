<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_damage_sums', function (Blueprint $table) {
            $table->id();
            $table->string('Damage_no')->unique();
            $table->date('Damage_date');
            $table->string('Store_code')->nullable();
            $table->decimal('Total_value', 15, 2)->default(0);
            $table->string('BC')->nullable();
            $table->string('OC')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_damage_details', function (Blueprint $table) {
            $table->id();
            $table->string('Damage_no');
            $table->string('Item_code');
            $table->string('Item_description')->nullable();
            $table->decimal('QTY', 15, 2);
            $table->decimal('Unit_price', 15, 2)->default(0);
            $table->decimal('Net_value', 15, 2)->default(0);
            $table->string('Reason')->nullable();
            $table->string('BC')->nullable();
            $table->string('OC')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_damage_details');
        Schema::dropIfExists('stock_damage_sums');
    }
};
