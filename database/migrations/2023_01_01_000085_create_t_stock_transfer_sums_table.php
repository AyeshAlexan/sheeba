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
            'CREATE TABLE `t_stock_transfer_sums` (

  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` varchar(255) DEFAULT NULL,
  `Branch_Code` varchar(60) DEFAULT NULL,
  `Branch_Name` varchar(75) DEFAULT NULL,
  `Branch_Address` varchar(255) DEFAULT NULL,
  `Branch_Phone` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Advance` decimal(10,2) DEFAULT NULL,
  `Balance` decimal(10,2) DEFAULT NULL,
  `Gross_Amount` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `To_Branch` varchar(50) DEFAULT NULL,
  `Is_Purchased` tinyint(1) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_stock_transfer_sums`
  ADD PRIMARY KEY (`Invoice_no`,`BC`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_stock_transfer_sums`');
    }
};
