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
            'CREATE TABLE `t_expenses` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Expense_no` varchar(255) NOT NULL,
  `Expense_date` varchar(255) NOT NULL,
  `ExpenseType` varchar(30) DEFAULT NULL,
  `Expense_note` varchar(255) NOT NULL,
  `Expense_Amount` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_expenses`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_expenses`');
    }
};
