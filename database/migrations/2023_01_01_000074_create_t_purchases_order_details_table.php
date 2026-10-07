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
            'CREATE TABLE `t_purchases_order_details` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Invoice_no` bigint(20) DEFAULT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Item_category` varchar(30) DEFAULT NULL,
  `Item_code` varchar(25) DEFAULT NULL,
  `Item_description` varchar(40) DEFAULT NULL,
  `QTY` double(8,2) DEFAULT NULL,
  `Unit_price` decimal(10,2) DEFAULT NULL,
  `Net_value` decimal(10,2) DEFAULT NULL,
  `BC` int(11) DEFAULT NULL,
  `OC` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_purchases_order_details`
  ADD PRIMARY KEY (`id`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_purchases_order_details`');
    }
};
