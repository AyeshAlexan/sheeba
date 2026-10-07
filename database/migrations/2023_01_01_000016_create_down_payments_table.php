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
            'CREATE TABLE `down_payments` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` bigint(20) DEFAULT NULL,
  `agreement_no` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `cus_code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `oc` varchar(50) DEFAULT NULL,
  `bc` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `down_payments`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `down_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `down_payments`');
    }
};
