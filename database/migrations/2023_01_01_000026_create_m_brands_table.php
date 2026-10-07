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
            'CREATE TABLE `m_brands` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Brand_code` varchar(255) NOT NULL,
  `Brand_name` varchar(255) NOT NULL,
  `Branch` varchar(30) DEFAULT NULL,
  `BranchCode` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m_brands_brand_code_unique` (`Brand_code`);',
            'ALTER TABLE `m_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_brands`');
    }
};
