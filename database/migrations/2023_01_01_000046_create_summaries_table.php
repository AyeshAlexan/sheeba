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
            'CREATE TABLE `summaries` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Pawning` varchar(255) NOT NULL,
  `Reedem` varchar(255) NOT NULL,
  `Profits` varchar(255) NOT NULL,
  `Date` varchar(255) NOT NULL,
  `Total_Amount` varchar(255) NOT NULL,
  `Expenses` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `summaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `summaries_pawning_unique` (`Pawning`),
  ADD UNIQUE KEY `summaries_reedem_unique` (`Reedem`);',
            'ALTER TABLE `summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `summaries`');
    }
};
