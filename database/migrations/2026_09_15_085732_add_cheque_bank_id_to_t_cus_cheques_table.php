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
        Schema::table('t_cus_cheques', function (Blueprint $table) {
            $table->unsignedBigInteger('cheque_bank_id')->nullable()->after('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_cus_cheques', function (Blueprint $table) {
            $table->dropColumn('cheque_bank_id');
        });
    }
};
