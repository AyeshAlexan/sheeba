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
            'CREATE TABLE `t_stock_transfer_details` (

  `id` bigint(20) NOT NULL,
  `Invoice_no` bigint(20) DEFAULT NULL,
  `Invoice_date` varchar(255) DEFAULT NULL,
  `Item_category` varchar(255) DEFAULT NULL,
  `Item_code` varchar(255) DEFAULT NULL,
  `Item_s_code` varchar(50) DEFAULT NULL,
  `Item_description` varchar(255) DEFAULT NULL,
  `QTY` bigint(20) DEFAULT NULL,
  `Unit_price` double(8,2) DEFAULT NULL,
  `Discount` double(8,2) DEFAULT NULL,
  `Net_value` double(8,2) DEFAULT NULL,
  `Is_Purchased` tinyint(1) DEFAULT NULL,
  `To_Branch` varchar(50) DEFAULT NULL,
  `BC` varchar(25) NOT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_stock_transfer_details`
  ADD PRIMARY KEY (`id`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_stock_transfer_details`');
    }
};
