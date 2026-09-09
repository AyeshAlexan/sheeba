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
        Schema::create('t_item_movements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trans_no');
            $table->string('trans_code');
            $table->string('item_code');
            $table->decimal('qun_in')->default('0');
            $table->decimal('qun_out')->default('0');
            $table->double('avarage')->default('0');
            $table->string('storse_id')->nullable();
            $table->date('dDate');
            $table->timestamp('action_date')->current_timestamp();
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
        Schema::dropIfExists('t_item_movements');
    }
};
