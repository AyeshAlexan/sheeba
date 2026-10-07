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
            'CREATE TABLE `t_purchases_sums` (

  `Invoice_no` bigint(20) NOT NULL,
  `Ref_no` bigint(20) DEFAULT NULL,
  `Invoice_date` varchar(25) DEFAULT NULL,
  `Customer_NIC` varchar(25) DEFAULT NULL,
  `Customer_Name` varchar(40) DEFAULT NULL,
  `Customer_Phone` varchar(25) DEFAULT NULL,
  `Customer_Address` varchar(40) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Advance` decimal(10,2) DEFAULT NULL,
  `Balance` decimal(10,2) DEFAULT NULL,
  `Gross_Amount` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `cash_payment` double(10,2) DEFAULT NULL,
  `credit_payment` double(10,2) DEFAULT NULL,
  `cheque_payment` double(10,2) DEFAULT NULL,
  `OC` varchar(25) DEFAULT NULL,
  `BC` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_purchases_sums`
  ADD PRIMARY KEY (`Invoice_no`,`BC`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_purchases_sums`');
    }
};
