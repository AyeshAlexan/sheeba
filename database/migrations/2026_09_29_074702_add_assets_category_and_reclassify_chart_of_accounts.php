<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Assets" was missing from the account-type master list entirely, so
     * Cash In Hand / Cheques had nowhere correct to sit and were filed
     * under Expenses/Income instead. Adds the missing type and moves
     * those two accounts to it.
     */
    public function up()
    {
        if (!DB::table('m_main_categories')->where('category', 'Assets')->exists()) {
            DB::table('m_main_categories')->insert([
                'code'     => '006',
                'category' => 'Assets',
            ]);
        }

        DB::table('m_chartof_accounts')
            ->whereIn('code', ['201-001', '201-123'])
            ->update([
                'accountsub' => 'Assets',
                'account'    => 'Current Assets',
            ]);
    }

    public function down()
    {
        DB::table('m_chartof_accounts')
            ->where('code', '201-001')
            ->update([
                'accountsub' => 'Expenses',
                'account'    => 'Administrative & Establishment',
            ]);

        DB::table('m_chartof_accounts')
            ->where('code', '201-123')
            ->update([
                'accountsub' => 'Income',
                'account'    => 'Finance & Other Expenses',
            ]);

        DB::table('m_main_categories')->where('category', 'Assets')->delete();
    }
};
