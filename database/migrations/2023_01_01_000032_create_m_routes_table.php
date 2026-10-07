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
            'CREATE TABLE `m_routes` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(25) NOT NULL,
  `description` varchar(50) NOT NULL,
  `BranchCode` varchar(50) NOT NULL,
  `Branch` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_routes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m_routes_code_unique` (`code`);',
            'ALTER TABLE `m_routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_routes`');
    }
};
