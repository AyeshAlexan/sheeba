<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('t_without_vat_sales_details', 'DiscountPercentage')) {
            Schema::table('t_without_vat_sales_details', function (Blueprint $table) {
                $table->decimal('DiscountPercentage', 5, 2)->default(0)->after('Discount');
            });
        }

        DB::statement(
            'UPDATE `t_without_vat_sales_details`
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
        if (Schema::hasColumn('t_without_vat_sales_details', 'DiscountPercentage')) {
            Schema::table('t_without_vat_sales_details', function (Blueprint $table) {
                $table->dropColumn('DiscountPercentage');
            });
        }
    }
};
