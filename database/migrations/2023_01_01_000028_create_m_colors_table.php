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
            'CREATE TABLE `m_colors` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Color_code` varchar(255) NOT NULL,
  `Color_name` varchar(255) NOT NULL,
  `Branch` varchar(30) DEFAULT NULL,
  `BranchCode` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m_colors_color_code_unique` (`Color_code`),
  ADD UNIQUE KEY `m_colors_color_name_unique` (`Color_name`);',
            'ALTER TABLE `m_colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_colors`');
    }
};
