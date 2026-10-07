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
            'CREATE TABLE `t_hire_purchase_sums` (

  `invoice_no` bigint(20) NOT NULL,
  `reference_no` bigint(20) DEFAULT NULL,
  `agreement_no` varchar(100) DEFAULT NULL,
  `invoice_date` varchar(255) DEFAULT NULL,
  `customer_code` varchar(255) DEFAULT NULL,
  `guarantor_1_code` bigint(20) DEFAULT NULL,
  `guarantor_2_code` bigint(20) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_nic` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `schema_type` varchar(255) DEFAULT NULL,
  `document_charge_rate` varchar(10) DEFAULT NULL,
  `document_charge` double(8,2) DEFAULT NULL,
  `down_payment_rate` varchar(10) DEFAULT NULL,
  `down_payment` double(8,2) DEFAULT NULL,
  `transport` double(8,2) DEFAULT NULL,
  `instalment_rate` varchar(10) DEFAULT NULL,
  `instalment_amount` double(8,2) DEFAULT NULL,
  `no_of_instalment` double(8,2) DEFAULT NULL,
  `instalment_due_date` varchar(255) DEFAULT NULL,
  `instalment` double(8,2) DEFAULT NULL,
  `final_gross_amount` decimal(10,2) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `gross_amount` double(8,2) DEFAULT NULL,
  `discount` double(8,2) DEFAULT NULL,
  `net_amount` double(8,2) DEFAULT NULL,
  `cash_payment` double(8,2) DEFAULT NULL,
  `card_payment` double(8,2) DEFAULT NULL,
  `cheque_payment` double(8,2) DEFAULT NULL,
  `bank_transfer` double(8,2) DEFAULT NULL,
  `oc` varchar(255) DEFAULT NULL,
  `bc` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `is_cash_converted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_hire_purchase_sums`
  ADD PRIMARY KEY (`invoice_no`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_hire_purchase_sums`');
    }
};
