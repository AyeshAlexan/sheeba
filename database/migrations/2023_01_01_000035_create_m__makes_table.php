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
            'CREATE TABLE `m__makes` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Make_code` varchar(255) NOT NULL,
  `Make_name` varchar(255) NOT NULL,
  `Branch` varchar(30) DEFAULT NULL,
  `BranchCode` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m__makes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `m__makes_make_code_unique` (`Make_code`);',
            'ALTER TABLE `m__makes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m__makes`');
    }
};
