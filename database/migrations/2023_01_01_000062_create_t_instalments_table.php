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
            'CREATE TABLE `t_instalments` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` bigint(20) DEFAULT NULL,
  `agreement_no` varchar(100) DEFAULT NULL,
  `invoice_date` varchar(255) DEFAULT NULL,
  `up_date` date DEFAULT NULL,
  `customer_code` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `instalment_date` varchar(255) DEFAULT NULL,
  `instalment_amount` double(8,2) DEFAULT NULL,
  `schema_type` varchar(50) DEFAULT NULL,
  `discount` decimal(50,0) DEFAULT NULL,
  `amount_pay` double(8,2) DEFAULT NULL,
  `cash_payment` double(8,2) DEFAULT NULL,
  `card_payment` double(8,2) DEFAULT NULL,
  `cheque_payment` double(8,2) DEFAULT NULL,
  `bank_transfer` double(8,2) DEFAULT NULL,
  `oc` varchar(255) DEFAULT NULL,
  `bc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_instalments`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_instalments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=587;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_instalments`');
    }
};
