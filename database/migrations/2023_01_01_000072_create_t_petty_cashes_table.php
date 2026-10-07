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
            'CREATE TABLE `t_petty_cashes` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `cramount` varchar(255) DEFAULT NULL,
  `crcode` varchar(255) DEFAULT NULL,
  `dramount` varchar(255) DEFAULT NULL,
  `drcode` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_petty_cashes`
  ADD PRIMARY KEY (`id`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_petty_cashes`');
    }
};
