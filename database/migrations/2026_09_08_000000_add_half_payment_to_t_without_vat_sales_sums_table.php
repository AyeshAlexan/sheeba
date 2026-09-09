<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_without_vat_sales_sums', function (Blueprint $table) {
            $table->decimal('Half_Payment', 15, 2)->default(0)->after('Cash_Pay');
        });
    }

    public function down(): void
    {
        Schema::table('t_without_vat_sales_sums', function (Blueprint $table) {
            $table->dropColumn('Half_Payment');
        });
    }
};
