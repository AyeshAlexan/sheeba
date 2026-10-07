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
            'CREATE TABLE `stock_damage_sums` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Damage_no` varchar(255) NOT NULL,
  `Damage_date` date NOT NULL,
  `Store_code` varchar(255) DEFAULT NULL,
  `Total_qty` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Total_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `BC` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `stock_damage_sums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_damage_sums_damage_no_unique` (`Damage_no`);',
            'ALTER TABLE `stock_damage_sums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `stock_damage_sums`');
    }
};
