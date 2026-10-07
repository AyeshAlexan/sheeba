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
            'CREATE TABLE `m_main_account_types` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(25) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_main_account_types`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `m_main_account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }

        DB::table('m_main_account_types')->insert([
            ['id' => 19, 'code' => '001', 'category' => 'Expenses', 'description' => 'Administrative & Establishment', 'created_at' => '2023-12-08 13:42:18', 'updated_at' => '2023-12-08 13:42:18'],
            ['id' => 20, 'code' => '002', 'category' => 'Expenses', 'description' => 'Finance & Other Expenses', 'created_at' => '2023-12-08 13:42:35', 'updated_at' => '2023-12-08 13:42:35'],
            ['id' => 21, 'code' => '003', 'category' => 'Expenses', 'description' => 'Selling & Distribuotion', 'created_at' => '2023-12-08 13:42:54', 'updated_at' => '2023-12-08 13:42:54'],
            ['id' => 22, 'code' => '004', 'category' => 'Income', 'description' => 'Cost of Sales', 'created_at' => '2023-12-08 13:43:17', 'updated_at' => '2023-12-08 13:43:24'],
            ['id' => 23, 'code' => '005', 'category' => 'Equity', 'description' => 'Current Assets', 'created_at' => '2023-12-08 13:43:54', 'updated_at' => '2023-12-08 13:43:54'],
        ]);
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_main_account_types`');
    }
};
