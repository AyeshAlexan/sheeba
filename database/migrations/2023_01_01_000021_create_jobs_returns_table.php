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
            'CREATE TABLE `jobs_returns` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `Customer_NIC` varchar(255) DEFAULT NULL,
  `Customer_Name` varchar(255) DEFAULT NULL,
  `Customer_Phone` varchar(255) DEFAULT NULL,
  `Job_no` bigint(20) NOT NULL,
  `Job_Return_No` bigint(20) NOT NULL,
  `Date` varchar(255) DEFAULT NULL,
  `Brand` varchar(255) DEFAULT NULL,
  `Device_Model` varchar(255) DEFAULT NULL,
  `IMEI_Number` varchar(255) DEFAULT NULL,
  `Item` varchar(255) DEFAULT NULL,
  `Amount` double(8,2) DEFAULT NULL,
  `Advance` double(8,2) DEFAULT NULL,
  `Balance` double(8,2) DEFAULT NULL,
  `Problem` varchar(255) DEFAULT NULL,
  `Serial_Number` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Product_Configuration` varchar(255) DEFAULT NULL,
  `Problem_Reported` varchar(255) DEFAULT NULL,
  `Product_Condition` varchar(255) DEFAULT NULL,
  `Technician` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `jobs_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobs_returns_job_no_unique` (`Job_no`),
  ADD UNIQUE KEY `jobs_returns_job_return_no_unique` (`Job_Return_No`);',
            'ALTER TABLE `jobs_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `jobs_returns`');
    }
};
