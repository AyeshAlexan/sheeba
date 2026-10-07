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
            'CREATE TABLE `t_cus_sale_trances` (

  `id` bigint(20) NOT NULL,
  `no` varchar(255) DEFAULT NULL,
  `customer` varchar(200) NOT NULL,
  `dr_trnce_code` varchar(20) DEFAULT NULL,
  `dr_trnce_no` varchar(40) DEFAULT \'\'\'\'\'\',
  `cr_trnce_code` varchar(20) DEFAULT NULL,
  `cr_trnce_no` varchar(20) DEFAULT \'\'\'\'\'\',
  `dr_amount` decimal(10,2) DEFAULT NULL,
  `cr_amount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(20) DEFAULT NULL,
  `oc` varchar(20) DEFAULT NULL,
  `trance_type` varchar(50) NOT NULL,
  `trance_no` varchar(255) NOT NULL,
  `dDate` date DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Display_Ref` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;',
            'ALTER TABLE `t_cus_sale_trances`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `t_cus_sale_trances`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=781;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_cus_sale_trances`');
    }
};
