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
            'CREATE TABLE `t_hire_purchase_to_sales` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `conversion_no` bigint(20) NOT NULL,
  `conversion_date` date NOT NULL,
  `invoice_no` bigint(20) NOT NULL,
  `agreement_no` varchar(255) DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `customer_nic` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `item_net_amount` double(10,2) NOT NULL,
  `down_payment` double(10,2) DEFAULT NULL,
  `transport` double(10,2) DEFAULT NULL,
  `total_instalment_amount` double(10,2) NOT NULL,
  `due_amount_as_discount` double(10,2) NOT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `amount` double(10,2) NOT NULL,
  `cash_payment` double(10,2) DEFAULT NULL,
  `card_payment` double(10,2) DEFAULT NULL,
  `cheque_payment` double(10,2) DEFAULT NULL,
  `bank_transfer` double(10,2) DEFAULT NULL,
  `oc` varchar(255) DEFAULT NULL,
  `bc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_hire_purchase_to_sales`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_hire_purchase_to_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_hire_purchase_to_sales`');
    }
};
