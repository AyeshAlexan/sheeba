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
            'CREATE TABLE `cancel__pawnings` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `S_No` varchar(255) NOT NULL,
  `Bill_type` varchar(255) NOT NULL,
  `Bill_No` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Cus_Name` varchar(255) NOT NULL,
  `Cus_Add` varchar(255) NOT NULL,
  `NIC` varchar(255) NOT NULL,
  `Weight` varchar(255) NOT NULL,
  `Amount` varchar(255) NOT NULL,
  `Cancel_By` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `cancel__pawnings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cancel__pawnings_s_no_unique` (`S_No`),
  ADD UNIQUE KEY `cancel__pawnings_bill_type_unique` (`Bill_type`);',
            'ALTER TABLE `cancel__pawnings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `cancel__pawnings`');
    }
};
