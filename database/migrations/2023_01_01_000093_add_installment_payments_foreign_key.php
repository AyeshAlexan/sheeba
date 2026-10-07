<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('ALTER TABLE `installment_payments`
  ADD CONSTRAINT `installment_payments_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `installment_schedules` (`id`) ON DELETE CASCADE;');
    }

    public function down()
    {
        DB::unprepared('ALTER TABLE `installment_payments` DROP FOREIGN KEY `installment_payments_schedule_id_foreign`');
    }
};
