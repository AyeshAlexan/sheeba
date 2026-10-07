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
            'CREATE TABLE `t_account_trans` (

  `id` bigint(20) NOT NULL,
  `trance_type` varchar(100) DEFAULT NULL,
  `Ddate` date DEFAULT NULL,
  `AccCode` varchar(20) DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `Description` varchar(50) DEFAULT NULL,
  `dr_amount` decimal(10,2) DEFAULT NULL,
  `cr_amount` decimal(10,2) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oc` varchar(10) DEFAULT NULL,
  `bc` varchar(10) DEFAULT NULL,
  `trance_no` varchar(10) DEFAULT NULL,
  `no` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_account_trans`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_account_trans`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3761;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_account_trans`');
    }
};
