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
            'CREATE TABLE `packages` (

  `id` int(10) UNSIGNED NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `pkg_code` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Branch` varchar(100) NOT NULL,
  `BranchCode` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            'ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `packages`');
    }
};
