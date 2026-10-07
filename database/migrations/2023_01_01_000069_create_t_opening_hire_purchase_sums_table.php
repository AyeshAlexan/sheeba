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
            'CREATE TABLE `t_opening_hire_purchase_sums` (

  `invoice_no` bigint(20) NOT NULL,
  `reference_no` bigint(20) DEFAULT NULL,
  `agreement_no` bigint(20) DEFAULT NULL,
  `invoice_date` varchar(40) DEFAULT NULL,
  `customer_code` varchar(40) DEFAULT NULL,
  `guarantor_1_code` bigint(20) DEFAULT NULL,
  `guarantor_2_code` bigint(20) DEFAULT NULL,
  `customer_name` varchar(40) DEFAULT NULL,
  `customer_nic` varchar(40) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `schema_type` varchar(30) DEFAULT NULL,
  `document_charge_rate` varchar(20) DEFAULT NULL,
  `document_charge` decimal(10,2) DEFAULT NULL,
  `down_payment_rate` varchar(10) DEFAULT NULL,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `transport` decimal(8,2) DEFAULT NULL,
  `instalment_rate` varchar(10) DEFAULT NULL,
  `instalment_amount` decimal(15,3) DEFAULT NULL,
  `no_of_instalment` decimal(10,2) DEFAULT NULL,
  `instalment_due_date` varchar(255) DEFAULT NULL,
  `instalment` decimal(10,2) DEFAULT NULL,
  `gross_amount` decimal(15,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `net_amount` decimal(12,2) DEFAULT NULL,
  `cash_payment` decimal(10,2) DEFAULT NULL,
  `card_payment` decimal(10,2) DEFAULT NULL,
  `cheque_payment` decimal(10,2) DEFAULT NULL,
  `bank_transfer` decimal(10,2) DEFAULT NULL,
  `oc` varchar(40) DEFAULT NULL,
  `bc` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_opening_hire_purchase_sums`
  ADD PRIMARY KEY (`invoice_no`,`bc`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_opening_hire_purchase_sums`');
    }
};
