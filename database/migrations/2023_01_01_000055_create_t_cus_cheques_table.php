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
            'CREATE TABLE `t_cus_cheques` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_type` varchar(255) NOT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `trans_no` varchar(255) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `branch_code` varchar(50) DEFAULT NULL,
  `cheques_no` varchar(255) NOT NULL,
  `trans_order_no` decimal(15,2) DEFAULT NULL,
  `acc_no` varchar(50) DEFAULT NULL,
  `amount` varchar(255) NOT NULL,
  `cheque_status` varchar(100) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `oc` varchar(50) DEFAULT NULL,
  `bc` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cheque_bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_changed_at` timestamp NULL DEFAULT NULL,
  `is_partial_payment` tinyint(1) DEFAULT NULL,
  `pending_amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_cus_cheques`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_cus_cheques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=389;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_cus_cheques`');
    }
};
