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
            'CREATE TABLE `installment_schedules` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_name` varchar(255) DEFAULT \'Installment Schedule\',
  `total_amount` decimal(15,2) NOT NULL,
  `total_months` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `status` varchar(255) DEFAULT \'active\',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            'ALTER TABLE `installment_schedules`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `installment_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `installment_schedules`');
    }
};
