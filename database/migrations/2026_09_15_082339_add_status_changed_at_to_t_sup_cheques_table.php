<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->timestamp('status_changed_at')->nullable()->after('cheque_status');
        });

        // Existing cheques predate the status workflow — treat them as already
        // issued (their balance effect, if any, already happened historically)
        // rather than surfacing years of old cheques on the new Issued Cheques screen.
        DB::table('t_sup_cheques')
            ->whereNull('cheque_status')
            ->update(['cheque_status' => 'ISSUED']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_sup_cheques', function (Blueprint $table) {
            $table->dropColumn('status_changed_at');
        });
    }
};
