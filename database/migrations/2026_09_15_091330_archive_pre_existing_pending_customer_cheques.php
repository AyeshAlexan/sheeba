<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The new Banking > Cheque Deposit / Return pages surface every customer
     * cheque that was never explicitly marked deposited/returned. That
     * includes ~124 cheques entered before this workflow existed, drowning
     * out real, actionable cheques. Archive that pre-existing backlog once
     * so the action pages only show cheques from this point forward.
     */
    public function up()
    {
        DB::table('t_cus_cheques')
            ->whereNotIn('cheque_status', ['deposit', 'return'])
            ->where('created_at', '<', now()->startOfDay())
            ->update([
                'cheque_status'     => 'ARCHIVED',
                'status_changed_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('t_cus_cheques')
            ->where('cheque_status', 'ARCHIVED')
            ->update([
                'cheque_status'     => 'P',
                'status_changed_at' => null,
            ]);
    }
};
