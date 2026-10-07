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
            'CREATE TABLE `t_item_movements` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_no` bigint(20) NOT NULL,
  `trans_code` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `qun_in` decimal(8,2) DEFAULT 0.00,
  `qun_out` decimal(8,2) DEFAULT 0.00,
  `Stock_adjuestment` decimal(15,2) DEFAULT NULL,
  `avarage` double NOT NULL DEFAULT 0,
  `storse_id` varchar(255) DEFAULT NULL,
  `dDate` date NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bc` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Free_Issues` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_item_movements`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_item_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18553;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_item_movements`');
    }
};
