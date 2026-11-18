<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 201-123 ("Cheques") is supposed to mean only cheques physically held,
 * not yet deposited — every other place that touches it (Opening Cheque,
 * Cheque Deposit/Return, Customer/Supplier cheque payments) treats it that
 * way. ChequeBankController::store was the one exception: it seeded each
 * bank account's own Opening Amount into this same account, so the Cash &
 * Cheque report's "Cheque Balance" ended up showing bank cash instead of
 * cheques. Gives each bank its own GL code and moves its existing
 * BANK_OPEN entry off 201-123 onto it.
 */
return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('cheque_banks', 'gl_code')) {
            Schema::table('cheque_banks', function (Blueprint $table) {
                $table->string('gl_code', 60)->nullable()->after('current_amount');
            });
        }

        $banks = DB::table('cheque_banks')->whereNull('gl_code')->get();

        foreach ($banks as $bank) {
            $code = '202-' . str_pad($bank->id, 3, '0', STR_PAD_LEFT);

            DB::table('cheque_banks')->where('id', $bank->id)->update(['gl_code' => $code]);

            if (!DB::table('m_chartof_accounts')->where('code', $code)->exists()) {
                DB::table('m_chartof_accounts')->insert([
                    'account'         => 'Current Assets',
                    'accountsub'      => 'Assets',
                    'code'            => $code,
                    'description'     => 'Bank — ' . $bank->bank_name . ' (' . $bank->account_no . ')',
                    'opening_balance' => 0,
                    'BC'              => $bank->BC,
                    'OC'              => $bank->OC,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            DB::table('t_account_trans')
                ->where('trance_type', 'BANK_OPEN')
                ->where('trance_no', $bank->id)
                ->where('AccCode', '201-123')
                ->update(['AccCode' => $code]);
        }

        // Orphaned BANK_OPEN rows — the cheque_banks row they came from was
        // deleted directly in the database at some point (there's no delete
        // button in the app), so there's no bank left to move them to. They
        // were always one-sided (no account to balance against), so the
        // only correct fix is to remove them rather than leave them
        // permanently stuck inside 201-123.
        $existingBankIds = DB::table('cheque_banks')->pluck('id');

        DB::table('t_account_trans')
            ->where('trance_type', 'BANK_OPEN')
            ->where('AccCode', '201-123')
            ->whereNotIn('trance_no', $existingBankIds)
            ->delete();
    }

    public function down()
    {
        $banks = DB::table('cheque_banks')->whereNotNull('gl_code')->get();

        foreach ($banks as $bank) {
            DB::table('t_account_trans')
                ->where('trance_type', 'BANK_OPEN')
                ->where('trance_no', $bank->id)
                ->where('AccCode', $bank->gl_code)
                ->update(['AccCode' => '201-123']);

            DB::table('m_chartof_accounts')->where('code', $bank->gl_code)->delete();
        }

        if (Schema::hasColumn('cheque_banks', 'gl_code')) {
            Schema::table('cheque_banks', function (Blueprint $table) {
                $table->dropColumn('gl_code');
            });
        }
    }
};
