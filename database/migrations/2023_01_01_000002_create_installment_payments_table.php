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
            'CREATE TABLE `installment_payments` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `installment_no` int(11) NOT NULL,
  `month_label` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `installment_amount` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) DEFAULT 0.00,
  `balance_remaining` decimal(15,2) DEFAULT 0.00,
  `payment_status` varchar(255) DEFAULT \'Pending\',
  `payment_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            'ALTER TABLE `installment_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installment_payments_schedule_id_foreign` (`schedule_id`);',
            'ALTER TABLE `installment_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `installment_payments`');
    }
};
