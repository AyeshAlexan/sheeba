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
            'CREATE TABLE `t_redeem_sums` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Receipt_Number` varchar(255) DEFAULT NULL,
  `Redeem_Date` varchar(255) DEFAULT NULL,
  `Redeem_Number` varchar(255) DEFAULT NULL,
  `Original_Pawn_Amount` double(8,2) DEFAULT NULL,
  `Payable_Pawn_Amount` double(8,2) DEFAULT NULL,
  `Paid_Interest` double(8,2) DEFAULT NULL,
  `Payable_Interest` double(8,2) DEFAULT NULL,
  `Stamp_Fee` double(8,2) DEFAULT NULL,
  `Document_Charges` double(8,2) DEFAULT NULL,
  `Advance_Balance` double(8,2) DEFAULT NULL,
  `Discount` double(8,2) DEFAULT NULL,
  `Payable_Total` double(8,2) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_redeem_sums`
  ADD PRIMARY KEY (`id`);'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_redeem_sums`');
    }
};
