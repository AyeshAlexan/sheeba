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
            'CREATE TABLE `package_items` (

  `id` int(10) UNSIGNED NOT NULL,
  `package_id` int(11) NOT NULL,
  `pkg_code` varchar(100) DEFAULT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;',
            'ALTER TABLE `package_items`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `package_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `package_items`');
    }
};
