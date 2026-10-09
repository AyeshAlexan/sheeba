<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared(
            'ALTER TABLE `items`
  ADD COLUMN `Batchwise` tinyint(1) DEFAULT 0 AFTER `Serialnumber`;'
        );
    }

    public function down()
    {
        DB::unprepared(
            'ALTER TABLE `items`
  DROP COLUMN `Batchwise`;'
        );
    }
};
