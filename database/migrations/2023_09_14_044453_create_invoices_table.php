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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Job_no')->unique()->nullable();
            $table->bigInteger('Invoice_no')->unique();
            $table->string('Invoice_date')->nullable();
            $table->string('Delivery_date')->nullable();
            $table->string('Reported_date')->nullable();
            $table->string('Technician')->nullable();
            $table->string('Customer_NIC')->nullable();
            $table->string('Customer_Name')->nullable();
            $table->string('Customer_Phone')->nullable();
            $table->string('Brand')->nullable();
            $table->string('Device_Model')->nullable();
            $table->string('IMEI_Number')->nullable();
            $table->string('Status')->nullable();

            $table->string('Item')->nullable();
            $table->string('Problem')->nullable();
            $table->float('Amount')->nullable();
            $table->float('Advance')->nullable();
            $table->float('Balance')->nullable();

            $table->string('Serial_Number')->nullable();
            $table->string('Password')->nullable();
            $table->string('Product_Configuration')->nullable();
            $table->string('Problem_Reported')->nullable();
            $table->string('Product_Condition')->nullable();
            $table->String('OC')->nullable()->nullable();
            $table->String('BC')->nullable()->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
