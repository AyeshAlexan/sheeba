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
            'CREATE TABLE `receipts` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Customer_NIC` varchar(255) DEFAULT NULL,
  `Customer_Address` varchar(255) DEFAULT NULL,
  `Customer_Phone` varchar(255) DEFAULT NULL,
  `Receipt_Type` varchar(255) DEFAULT NULL,
  `Receipt_Number` varchar(255) DEFAULT NULL,
  `Receipt_Date` varchar(255) DEFAULT NULL,
  `Date` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `Articles` varchar(255) DEFAULT NULL,
  `Condition` varchar(255) DEFAULT NULL,
  `Karatage` varchar(255) DEFAULT NULL,
  `Weight` int(11) DEFAULT NULL,
  `QTY` int(11) DEFAULT NULL,
  `Value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `receipts`');
    }
};
