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
            'CREATE TABLE `t_without_vat_sales_sums` (

  `id` bigint(20) NOT NULL,
  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` date DEFAULT NULL,
  `ref_no` varchar(200) DEFAULT NULL,
  `Customer_NIC` varchar(15) DEFAULT NULL,
  `Customer_Name` varchar(500) DEFAULT NULL,
  `Customer_Phone` varchar(12) DEFAULT NULL,
  `customer_balance` decimal(15,2) DEFAULT NULL,
  `Route` varchar(50) DEFAULT NULL,
  `Salesmen` varchar(50) DEFAULT NULL,
  `Item` varchar(30) DEFAULT NULL,
  `Amount` decimal(15,2) DEFAULT NULL,
  `Advance` decimal(15,2) DEFAULT NULL,
  `Balance` decimal(15,2) DEFAULT NULL,
  `Gross_Amount` decimal(15,2) DEFAULT NULL,
  `Discount` decimal(15,2) DEFAULT NULL,
  `Net_Amount` decimal(15,2) DEFAULT NULL,
  `vat_presentage` double DEFAULT NULL,
  `vat_amount` decimal(15,2) DEFAULT NULL,
  `after_vat_amount` decimal(15,2) DEFAULT NULL,
  `Cash_Pay` decimal(15,2) DEFAULT NULL,
  `Credite` decimal(15,2) DEFAULT NULL,
  `Cheque` decimal(15,2) DEFAULT NULL,
  `invoice_remark` varchar(255) DEFAULT NULL,
  `Paid_Amount` decimal(15,2) DEFAULT 0.00,
  `customer_type` varchar(20) DEFAULT NULL,
  `after_customer_discount` decimal(15,2) DEFAULT NULL,
  `OC` varchar(25) DEFAULT NULL,
  `BC` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `print_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_printed_at` timestamp NULL DEFAULT NULL,
  `Half_Payment` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_without_vat_sales_sums`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_without_vat_sales_sums`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_without_vat_sales_sums`');
    }
};
