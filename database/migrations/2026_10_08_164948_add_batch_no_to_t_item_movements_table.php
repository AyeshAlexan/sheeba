<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared(
            'ALTER TABLE `t_item_movements`
  ADD COLUMN `batch_no` varchar(255) DEFAULT NULL AFTER `item_code`,
  ADD KEY `t_item_movements_item_code_batch_no_index` (`item_code`,`batch_no`);'
        );
    }

    public function down()
    {
        DB::unprepared(
            'ALTER TABLE `t_item_movements`
  DROP KEY `t_item_movements_item_code_batch_no_index`,
  DROP COLUMN `batch_no`;'
        );
    }
};
