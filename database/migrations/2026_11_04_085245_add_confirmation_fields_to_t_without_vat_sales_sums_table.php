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
        Schema::table('t_without_vat_sales_sums', function (Blueprint $table) {
            // Replaces the separate Delivery Order table/flow: this single
            // nullable timestamp doubles as both the "is this invoice
            // confirmed" status flag and the audit timestamp. null = stock
            // not yet moved; set = stock deducted via Confirm Invoice.
            $table->timestamp('confirmed_at')->nullable()->after('Half_Payment');
            $table->string('confirmed_by')->nullable()->after('confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_without_vat_sales_sums', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'confirmed_by']);
        });
    }
};
