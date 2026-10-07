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
            'CREATE TABLE `technicians` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Code` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Gender` varchar(255) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address_1` varchar(255) NOT NULL,
  `Address_2` varchar(255) DEFAULT NULL,
  `Contact_1` varchar(255) NOT NULL,
  `Contact_2` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `NIC` varchar(255) DEFAULT NULL,
  `Driving_license` varchar(255) DEFAULT NULL,
  `Passport` varchar(255) DEFAULT NULL,
  `Other_identifications` varchar(255) DEFAULT NULL,
  `Status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `technicians_code_unique` (`Code`);',
            'ALTER TABLE `technicians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `technicians`');
    }
};
