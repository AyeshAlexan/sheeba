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
            'CREATE TABLE `m_main_categories` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(25) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_main_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m_main_categories_code_unique` (`code`);',
            'ALTER TABLE `m_main_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }

        DB::table('m_main_categories')->insert([
            ['id' => 2, 'code' => '002', 'category' => 'Expenses', 'created_at' => '2023-12-07 13:48:21', 'updated_at' => '2023-12-08 13:38:11'],
            ['id' => 3, 'code' => '003', 'category' => 'Liabilities', 'created_at' => '2023-12-08 13:36:02', 'updated_at' => '2023-12-08 13:36:02'],
            ['id' => 4, 'code' => '004', 'category' => 'Equity', 'created_at' => '2023-12-08 13:36:16', 'updated_at' => '2023-12-08 13:36:16'],
            ['id' => 5, 'code' => '005', 'category' => 'Revenue', 'created_at' => '2023-12-08 13:36:28', 'updated_at' => '2023-12-08 13:36:28'],
            ['id' => 6, 'code' => '001', 'category' => 'Income', 'created_at' => '2023-12-08 13:38:51', 'updated_at' => '2023-12-08 13:38:51'],
            ['id' => 19, 'code' => '006', 'category' => 'Assets', 'created_at' => null, 'updated_at' => null],
        ]);
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_main_categories`');
    }
};
