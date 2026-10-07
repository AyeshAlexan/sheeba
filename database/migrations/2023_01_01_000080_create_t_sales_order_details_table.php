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
            'CREATE TABLE `t_sales_order_details` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Invoice_no` bigint(20) DEFAULT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Item_category` varchar(50) DEFAULT NULL,
  `Item_code` varchar(50) DEFAULT NULL,
  `Item_s_code` varchar(100) DEFAULT NULL,
  `Item_description` varchar(50) DEFAULT NULL,
  `QTY` double(8,3) DEFAULT NULL,
  `Unit_price` double(8,2) DEFAULT NULL,
  `Discount` decimal(30,0) DEFAULT NULL,
  `Net_value` decimal(10,2) DEFAULT NULL,
  `BC` varchar(50) DEFAULT NULL,
  `OC` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_sales_order_details`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_sales_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_sales_order_details`');
    }
};
