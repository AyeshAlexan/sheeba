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
            'CREATE TABLE `t_item_serial_movements` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_no` bigint(20) NOT NULL,
  `trans_code` varchar(255) NOT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_serial_no` varchar(255) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `qun_in` decimal(8,2) DEFAULT 0.00,
  `qun_out` decimal(8,2) DEFAULT 0.00,
  `avarage` double DEFAULT 0,
  `storse_id` varchar(255) DEFAULT NULL,
  `dDate` date NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_item_serial_movements`
  ADD PRIMARY KEY (`id`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_item_serial_movements`');
    }
};
