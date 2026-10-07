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
            'CREATE TABLE `t_purchases_return_sums` (

  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Supplier_Code` varchar(30) DEFAULT NULL,
  `Supplier_Name` varchar(50) DEFAULT NULL,
  `Supplier_Phone` varchar(15) DEFAULT NULL,
  `Gross_Amount` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `OC` varchar(40) DEFAULT NULL,
  `BC` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_purchases_return_sums`
  ADD PRIMARY KEY (`Invoice_no`,`BC`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_purchases_return_sums`');
    }
};
