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
            'CREATE TABLE `day_end_balances` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `BC` varchar(255) NOT NULL,
  `close_date` date NOT NULL,
  `closing_balance` decimal(15,2) NOT NULL,
  `cash_closing_balance` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT \'Closing balance for AccCode 201-001 (Cash)\',
  `cheque_closing_balance` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT \'Closing balance for AccCode 201-123 (Cheque)\',
  `total_dr` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cr` decimal(15,2) NOT NULL DEFAULT 0.00,
  `closed_by` bigint(20) UNSIGNED NOT NULL,
  `closed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            'ALTER TABLE `day_end_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day_end_balances_bc_close_date_unique` (`BC`,`close_date`),
  ADD KEY `day_end_balances_closed_by_foreign` (`closed_by`);',
            'ALTER TABLE `day_end_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `day_end_balances`');
    }
};
