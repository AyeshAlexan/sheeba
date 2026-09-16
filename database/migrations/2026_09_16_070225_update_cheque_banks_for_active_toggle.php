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
        Schema::table('cheque_banks', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('current_amount');
        });

        // Account Name is no longer collected on the form — relax the
        // NOT NULL constraint via raw SQL (doctrine/dbal isn't installed,
        // so Blueprint::change() isn't available in this app).
        DB::statement('ALTER TABLE cheque_banks MODIFY account_name VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cheque_banks', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
