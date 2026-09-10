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
        Schema::table('t_cus_sale_trances', function (Blueprint $table) {
            $table->string('Display_Ref')->nullable()->after('trance_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_cus_sale_trances', function (Blueprint $table) {
            $table->dropColumn('Display_Ref');
        });
    }
};
