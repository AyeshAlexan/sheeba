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
            $table->boolean('is_reconciled')->default(false)->after('cheque_status');
            $table->timestamp('reconciled_at')->nullable()->after('is_reconciled');
            $table->unsignedBigInteger('reconciled_by')->nullable()->after('reconciled_at');
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
            $table->dropColumn(['is_reconciled', 'reconciled_at', 'reconciled_by']);
        });
    }
};
