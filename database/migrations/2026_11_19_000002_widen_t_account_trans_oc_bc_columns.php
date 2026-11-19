<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `oc`/`bc` were created as varchar(10), but they store the logged-in
 * user's username (e.g. "SheebaSP 01"), which can exceed 10 characters
 * and causes "Data too long for column 'oc'" on sales invoice save.
 * Widen them to match the size used for the same columns elsewhere
 * (e.g. t_customer_payments, t_supplier_payments).
 */
return new class extends Migration
{
    public function up()
    {
        DB::unprepared('ALTER TABLE `t_account_trans`
            MODIFY `oc` varchar(255) DEFAULT NULL,
            MODIFY `bc` varchar(255) DEFAULT NULL;');
    }

    public function down()
    {
        DB::unprepared('ALTER TABLE `t_account_trans`
            MODIFY `oc` varchar(10) DEFAULT NULL,
            MODIFY `bc` varchar(10) DEFAULT NULL;');
    }
};
