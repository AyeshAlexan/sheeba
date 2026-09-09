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
        Schema::create('t_hire_purchase_to_sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversion_no');
            $table->date('conversion_date');
            $table->bigInteger('invoice_no');
            $table->string('agreement_no');
            $table->date('invoice_date');
            $table->string('customer_nic');
            $table->string('customer_name');
            $table->string('customer_phone');

            $table->double('item_net_amount',10,2);
            $table->double('down_payment',10,2);
            $table->double('transport',10,2);
            $table->double('total_instalment_amount',10,2);
            $table->double('due_amount_as_discount',10,2);
            $table->double('discount',10,2);
            $table->double('amount',10,2);

            $table->double('cash_payment',10,2);
            $table->double('card_payment',10,2);
            $table->double('cheque_payment',10,2);
            $table->double('bank_transfer',10,2);

            $table->string('oc');
            $table->string('bc');
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
        Schema::dropIfExists('t_hire_purchase_to_sales');
    }
};
