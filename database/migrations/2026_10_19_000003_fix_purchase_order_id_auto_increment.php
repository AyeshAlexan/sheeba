<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * t_purchase_order_sums and t_purchases_order_details were created with
 * `id` as the primary key but never marked AUTO_INCREMENT, so every insert
 * failed with "Field 'id' doesn't have a default value" — Purchase Order
 * creation has never been able to save a row. Both tables are empty, so
 * there's no existing data to worry about.
 */
return new class extends Migration
{
    public function up()
    {
        DB::unprepared('ALTER TABLE `t_purchase_order_sums` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::unprepared('ALTER TABLE `t_purchases_order_details` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
    }

    public function down()
    {
        DB::unprepared('ALTER TABLE `t_purchase_order_sums` MODIFY `id` bigint(20) UNSIGNED NOT NULL;');
        DB::unprepared('ALTER TABLE `t_purchases_order_details` MODIFY `id` bigint(20) UNSIGNED NOT NULL;');
    }
};
