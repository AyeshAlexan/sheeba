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
        Schema::table('t_sup_cheques', function (Blueprint $table) {
            // The supplier's own receiving bank — free text since we can't
            // maintain a dropdown of every supplier's bank. Separate from
            // `cheque_bank_id`/`bank`, which represent OUR bank the cheque
            // is drawn from.
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
        Schema::table('t_sup_cheques', function (Blueprint $table) {
            $table->dropColumn('other_bank_name');
        });
    }
};
