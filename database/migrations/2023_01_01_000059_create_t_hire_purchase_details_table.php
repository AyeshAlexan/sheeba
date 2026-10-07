<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Schema reconstructed directly from a real production dump
 * (smarto_sheeba1.sql) — exact column types/defaults/indexes, no guessing.
 */
return new class extends Migration
{
    public function up()
    {
        $statements = [
            'CREATE TABLE `t_hire_purchase_details` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` bigint(20) DEFAULT NULL,
  `invoice_date` varchar(255) DEFAULT NULL,
  `item_category` varchar(255) DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `Item_s_code` varchar(50) DEFAULT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `unit_price` double(8,2) DEFAULT NULL,
  `qty` double(8,2) DEFAULT NULL,
  `discount_precentage` double(8,2) DEFAULT NULL,
  `discount` double(8,2) DEFAULT NULL,
  `net_value` double(8,2) DEFAULT NULL,
  `oc` varchar(255) DEFAULT NULL,
  `bc` varchar(255) DEFAULT NULL,
  `is_cash_converted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_hire_purchase_details`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_hire_purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_hire_purchase_details`');
    }
};
