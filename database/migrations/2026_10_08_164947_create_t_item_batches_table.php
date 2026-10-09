<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $statements = [
            'CREATE TABLE `t_item_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `batch_no` varchar(255) NOT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `is_auto_generated` tinyint(1) NOT NULL DEFAULT 1,
  `source_trans_code` varchar(255) DEFAULT NULL,
  `source_trans_no` bigint(20) DEFAULT NULL,
  `bc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `t_item_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_item_batches_item_code_batch_no_unique` (`item_code`,`batch_no`),
  ADD KEY `t_item_batches_item_code_index` (`item_code`);',
            'ALTER TABLE `t_item_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `t_item_batches`');
    }
};
