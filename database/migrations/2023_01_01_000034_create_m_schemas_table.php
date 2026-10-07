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
            'CREATE TABLE `m_schemas` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `SchemaType` varchar(255) NOT NULL,
  `InRate` decimal(10,2) DEFAULT NULL,
  `DocumentCharage` decimal(10,2) DEFAULT NULL,
  `PanaltyCharage` decimal(10,2) DEFAULT NULL,
  `DownPayment` decimal(10,2) DEFAULT NULL,
  `Branch` varchar(20) DEFAULT NULL,
  `BC` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `OC` varchar(10) NOT NULL DEFAULT \'\'\'\'\'\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_schemas`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `m_schemas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_schemas`');
    }
};
