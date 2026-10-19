<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $statements = [
            'CREATE TABLE `t_delivery_order_sums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `DO_no` bigint(20) NOT NULL,
  `DO_date` date DEFAULT NULL,
  `Invoice_no` bigint(20) DEFAULT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Customer_NIC` varchar(15) DEFAULT NULL,
  `Customer_Name` varchar(500) DEFAULT NULL,
  `Customer_Phone` varchar(12) DEFAULT NULL,
  `Gross_Amount` decimal(15,2) DEFAULT NULL,
  `Discount` decimal(15,2) DEFAULT NULL,
  `Net_Amount` decimal(15,2) DEFAULT NULL,
  `Delivered_By` varchar(255) DEFAULT NULL,
  `Received_By` varchar(255) DEFAULT NULL,
  `OC` varchar(25) DEFAULT NULL,
  `BC` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_delivery_order_sums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_delivery_order_sums_do_no_bc_unique` (`DO_no`,`BC`),
  ADD KEY `t_delivery_order_sums_invoice_no_index` (`Invoice_no`);',
            'ALTER TABLE `t_delivery_order_sums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_delivery_order_sums`');
    }
};
