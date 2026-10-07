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
            'CREATE TABLE `stock_damage_details` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Damage_no` varchar(255) NOT NULL,
  `Item_code` varchar(255) NOT NULL,
  `Item_description` varchar(255) DEFAULT NULL,
  `QTY` decimal(15,2) NOT NULL,
  `Unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Net_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `Reason` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `stock_damage_details`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `stock_damage_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `stock_damage_details`');
    }
};
