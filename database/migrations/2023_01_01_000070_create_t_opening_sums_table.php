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
            'CREATE TABLE `t_opening_sums` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Store_code` varchar(10) DEFAULT NULL,
  `Invoice_no` bigint(20) NOT NULL,
  `Invoice_date` date DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `OC` varchar(25) DEFAULT NULL,
  `BC` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Store_name` varchar(255) NOT NULL DEFAULT \'\'\'\'\'\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_opening_sums`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_opening_sums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_opening_sums`');
    }
};
