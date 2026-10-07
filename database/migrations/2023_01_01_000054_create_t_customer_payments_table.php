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
            'CREATE TABLE `t_customer_payments` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Payment_no` bigint(20) NOT NULL,
  `Sales_no` varchar(255) NOT NULL,
  `Payment_date` varchar(255) NOT NULL,
  `Customer_Code` varchar(15) DEFAULT NULL,
  `Customer_Name` varchar(60) DEFAULT NULL,
  `Customer_Phone` varchar(15) DEFAULT NULL,
  `Payment_note` varchar(255) DEFAULT NULL,
  `Payment_Amount` varchar(255) NOT NULL,
  `Paided_amount` decimal(15,2) DEFAULT NULL,
  `cash_payment` decimal(15,2) DEFAULT NULL,
  `card_payment` decimal(15,2) DEFAULT NULL,
  `cheque_payment` decimal(15,2) DEFAULT NULL,
  `bank_transfer` decimal(15,2) DEFAULT NULL,
  `BC` varchar(255) NOT NULL,
  `OC` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_customer_payments`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_customer_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=824;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_customer_payments`');
    }
};
