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
            'CREATE TABLE `t_sales_order_sums` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Delivery_date` date DEFAULT NULL,
  `Customer_NIC` varchar(15) DEFAULT NULL,
  `Customer_Name` varchar(50) DEFAULT NULL,
  `Customer_Phone` int(11) DEFAULT NULL,
  `Gross_Amount` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `Cash_Pay` decimal(10,2) DEFAULT NULL,
  `Credite` decimal(10,2) DEFAULT NULL,
  `Cheque` decimal(10,2) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `OC` varchar(50) DEFAULT NULL,
  `BC` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_sales_order_sums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_sales_order_sums_invoice_no_unique` (`Invoice_no`);',
            'ALTER TABLE `t_sales_order_sums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_sales_order_sums`');
    }
};
