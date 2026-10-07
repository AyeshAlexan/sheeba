<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        foreach (['t_invoice_sums', 't_without_vat_sales_sums', 't_sales_return_sums'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedInteger('print_count')->default(0);
                $blueprint->timestamp('last_printed_at')->nullable();
            });
        }
    }

    public function down()
    {
        foreach (['t_invoice_sums', 't_without_vat_sales_sums', 't_sales_return_sums'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn(['print_count', 'last_printed_at']);
            });
        }
    }
};
