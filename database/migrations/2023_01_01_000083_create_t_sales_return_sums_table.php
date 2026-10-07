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
            'CREATE TABLE `t_sales_return_sums` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Delivery_date` date DEFAULT NULL,
  `Customer_NIC` varchar(50) DEFAULT NULL,
  `Customer_Name` varchar(60) DEFAULT NULL,
  `Customer_Phone` varchar(15) DEFAULT NULL,
  `Gross_Amount` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `Cash_Pay` decimal(10,2) DEFAULT NULL,
  `Credite` decimal(10,2) DEFAULT NULL,
  `Cheque` decimal(10,2) DEFAULT NULL,
  `OC` varchar(30) DEFAULT NULL,
  `BC` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `print_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_printed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_sales_return_sums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_sales_return_sums_invoice_no_unique` (`Invoice_no`);',
            'ALTER TABLE `t_sales_return_sums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_sales_return_sums`');
    }
};
