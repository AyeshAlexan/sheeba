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
            'CREATE TABLE `t_advanc_cus_payments` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nic` varchar(16) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `cus_code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_advanc_cus_payments`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_advanc_cus_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_advanc_cus_payments`');
    }
};
