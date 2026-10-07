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
            'CREATE TABLE `t_payment_vouchers` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `cramount` varchar(255) DEFAULT NULL,
  `crcode` varchar(20) DEFAULT NULL,
  `dramount` varchar(255) DEFAULT NULL,
  `drcode` varchar(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_payment_vouchers`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_payment_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=904;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_payment_vouchers`');
    }
};
