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
            $table->boolean('is_partial_payment')->nullable()->after('amount');
            $table->decimal('pending_amount', 15, 2)->nullable()->after('is_partial_payment');
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
            $table->dropColumn(['is_partial_payment', 'pending_amount']);
        });
    }
};
