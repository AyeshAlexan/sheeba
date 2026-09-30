<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * C-001 and C-002 were each shared by two unrelated accounts. Keeps the
     * code on the longer-standing account in each pair (Electricity Bill,
     * Staff Salary Allowance) and renumbers the other to a free code.
     *
     * Note: this only fixes the Chart of Accounts entry itself. Historical
     * t_account_trans rows recorded against the shared code can't be split
     * retroactively — the ledger never recorded which of the two accounts
     * a given entry actually belonged to, only the code. New transactions
     * posted against the renumbered accounts from now on will be correct.
     */
    public function up()
    {
        DB::table('m_chartof_accounts')->where('id', 60)->update(['code' => 'C-104']); // Indra J/W
        DB::table('m_chartof_accounts')->where('id', 59)->update(['code' => 'C-105']); // Income Receivab
    }

    public function down()
    {
        DB::table('m_chartof_accounts')->where('id', 60)->update(['code' => 'C-001']);
        DB::table('m_chartof_accounts')->where('id', 59)->update(['code' => 'C-002']);
    }
};
