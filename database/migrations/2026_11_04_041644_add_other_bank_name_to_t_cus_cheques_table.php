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
            // The customer's/payee's own bank (where the cheque is drawn from
            // their side) — free text since we can't maintain a dropdown of
            // every customer's bank. Separate from `cheque_bank_id`/`bank`,
            // which represent OUR bank the cheque is deposited into.
            $table->string('other_bank_name')->nullable()->after('bank');
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
            $table->dropColumn('other_bank_name');
        });
    }
};
