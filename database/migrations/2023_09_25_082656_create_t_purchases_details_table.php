<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_purchases_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Job_no')->nullable();
            $table->bigInteger('Invoice_no')->nullable();
            $table->string('Invoice_date')->nullable();
            $table->string('Item_category')->nullable();
            $table->string('Item_code')->nullable();
            $table->string('Item_description')->nullable();
            $table->bigInteger('QTY')->nullable();
            $table->float('Unit_price')->nullable();
            $table->float('Net_value')->nullable();

            $table->String('OC')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_purchases_details');
    }
};
