<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared(
            'ALTER TABLE `t_without_vat_sales_details`
  ADD COLUMN `batch_no` varchar(255) DEFAULT NULL AFTER `Item_code`,
  ADD KEY `t_without_vat_sales_details_item_code_batch_no_index` (`Item_code`,`batch_no`);'
        );
    }

    public function down()
    {
        DB::unprepared(
            'ALTER TABLE `t_without_vat_sales_details`
  DROP KEY `t_without_vat_sales_details_item_code_batch_no_index`,
  DROP COLUMN `batch_no`;'
        );
    }
};
