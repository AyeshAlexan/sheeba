<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('t_invoice_deils', 'DiscountPercentage')) {
            Schema::table('t_invoice_deils', function (Blueprint $table) {
                $table->decimal('DiscountPercentage', 5, 2)->default(0)->after('Discount');
            });
        }

        DB::statement(
            'UPDATE `t_invoice_deils`
             SET `DiscountPercentage` = CASE
                 WHEN `QTY` * `Unit_price` > 0
                 THEN ROUND(`Discount` / (`QTY` * `Unit_price`) * 100, 2)
                 ELSE 0
             END
             WHERE `Discount` > 0 AND `DiscountPercentage` = 0'
        );
    }

    public function down()
    {
        if (Schema::hasColumn('t_invoice_deils', 'DiscountPercentage')) {
            Schema::table('t_invoice_deils', function (Blueprint $table) {
                $table->dropColumn('DiscountPercentage');
            });
        }
    }
};
