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
            'CREATE TABLE `daily_transactions` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `dr_code` varchar(255) NOT NULL,
  `dr_label` varchar(255) NOT NULL,
  `cr_code` varchar(255) NOT NULL,
  `cr_label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `party_type` varchar(255) DEFAULT NULL,
  `party_code` varchar(255) DEFAULT NULL,
  `party_side` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `daily_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_transactions_ref_no_unique` (`ref_no`);',
            'ALTER TABLE `daily_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `daily_transactions`');
    }
};
