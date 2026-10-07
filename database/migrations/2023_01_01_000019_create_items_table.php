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
            'CREATE TABLE `items` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(30) DEFAULT NULL,
  `Department` varchar(25) DEFAULT NULL,
  `Item_code` varchar(255) NOT NULL,
  `Bar_code` varchar(255) DEFAULT NULL,
  `Item_description` varchar(255) NOT NULL,
  `Brand` varchar(25) DEFAULT NULL,
  `Color` varchar(25) DEFAULT NULL,
  `Make` varchar(25) DEFAULT NULL,
  `image` varchar(11) DEFAULT NULL,
  `purchasePrice` decimal(10,2) DEFAULT NULL,
  `wholesaleprice` decimal(10,2) DEFAULT NULL,
  `creditprice` decimal(10,2) DEFAULT NULL,
  `bulkprice` decimal(10,2) DEFAULT NULL,
  `saleprice` decimal(10,2) DEFAULT NULL,
  `Credit` varchar(30) DEFAULT NULL,
  `Inactive` tinyint(1) DEFAULT NULL,
  `Per` varchar(20) DEFAULT NULL,
  `ReorderLevel` varchar(25) DEFAULT NULL,
  `RecorderQuantitiy` varchar(25) DEFAULT NULL,
  `SaleDecimal` tinyint(1) DEFAULT NULL,
  `Serialnumber` tinyint(1) DEFAULT NULL,
  `Branch` varchar(30) DEFAULT NULL,
  `BranchCode` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_item_code_unique` (`Item_code`);',
            'ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17321;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `items`');
    }
};
