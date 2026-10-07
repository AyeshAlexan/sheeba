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
            'CREATE TABLE `advance__payment__balances` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `S_No` varchar(255) NOT NULL,
  `Bill_type` varchar(255) NOT NULL,
  `Bill_No` varchar(255) NOT NULL,
  `Cus_Name` varchar(255) NOT NULL,
  `Cus_Address` varchar(255) NOT NULL,
  `NIC` varchar(255) NOT NULL,
  `Mobile` varchar(255) NOT NULL,
  `Total_Weight` varchar(255) NOT NULL,
  `Date_Time` date NOT NULL,
  `Balance` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `advance__payment__balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `advance__payment__balances_s_no_unique` (`S_No`),
  ADD UNIQUE KEY `advance__payment__balances_bill_type_unique` (`Bill_type`);',
            'ALTER TABLE `advance__payment__balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `advance__payment__balances`');
    }
};
