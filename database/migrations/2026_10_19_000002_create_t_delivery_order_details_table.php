<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $statements = [
            'CREATE TABLE `t_delivery_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `DO_no` bigint(20) DEFAULT NULL,
  `DO_date` date DEFAULT NULL,
  `Item_category` varchar(255) DEFAULT NULL,
  `Item_code` varchar(255) DEFAULT NULL,
  `Item_s_code` varchar(50) DEFAULT NULL,
  `Item_description` varchar(255) DEFAULT NULL,
  `QTY` double(8,3) DEFAULT NULL,
  `Unit_price` double(8,2) DEFAULT NULL,
  `Discount` double(8,2) DEFAULT NULL,
  `Net_value` double(8,2) DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `BC` varchar(25) NOT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_delivery_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_delivery_order_details_do_no_index` (`DO_no`),
  ADD KEY `t_delivery_order_details_item_code_batch_no_index` (`Item_code`,`batch_no`);',
            'ALTER TABLE `t_delivery_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_delivery_order_details`');
    }
};
