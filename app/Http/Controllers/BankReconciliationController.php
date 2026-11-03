<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\ChequeBank;

class BankReconciliationController extends Controller
{
    /**
     * Unified "Banking Recognition" queue: every cheque that has actually
     * moved against a bank account (supplier: Issued/Deposited/Returned,
     * customer: Deposited/Returned) so it can be ticked off against the
     * real bank statement, filtered by bank and date.
     */
    public function index(Request $request)
    {
        $bankId   = $request->input('cheque_bank_id');
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $supplierQuery = TSupCheque::leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_sup_cheques.cheque_bank_id')
            ->whereIn('t_sup_cheques.cheque_status', ['ISSUED', 'DEPOSITED', 'RETURNED'])
            ->select(
                't_sup_cheques.id',
                't_sup_cheques.cheque_bank_id',
                't_sup_cheques.cheques_no',
                't_sup_cheques.cheque_status',
                't_sup_cheques.supplier_name as party',
                't_sup_cheques.release_date',
                't_sup_cheques.amount',
                't_sup_cheques.is_reconciled',
                'cheque_banks.bank_name'
            );

        $customerQuery = TCusCheque::leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_cus_cheques.cheque_bank_id')
            ->leftJoin('customers', 'customers.Code', '=', 't_cus_cheques.trans_no')
            ->whereIn('t_cus_cheques.cheque_status', ['deposit', 'return'])
            ->select(
                't_cus_cheques.id',
                't_cus_cheques.cheque_bank_id',
                't_cus_cheques.cheques_no',
                't_cus_cheques.cheque_status',
                'customers.Name as party',
                't_cus_cheques.release_date',
                't_cus_cheques.amount',
                't_cus_cheques.is_reconciled',
                'cheque_banks.bank_name'
            );

        if ($bankId) {
            $supplierQuery->where('t_sup_cheques.cheque_bank_id', $bankId);
            $customerQuery->where('t_cus_cheques.cheque_bank_id', $bankId);
        }

        if ($fromDate && $toDate) {
            $supplierQuery->whereBetween('t_sup_cheques.release_date', [$fromDate, $toDate]);
            $customerQuery->whereBetween('t_cus_cheques.release_date', [$fromDate, $toDate]);
        }

        $supplierCheques = $supplierQuery->get()->map(function ($row) {
            $row->direction = 'supplier';
            $row->status_label = ucfirst(strtolower($row->cheque_status));
            return $row;
        });

        $customerCheques = $customerQuery->get()->map(function ($row) {
            $row->direction = 'customer';
            $row->status_label = $row->cheque_status === 'deposit' ? 'Deposited' : 'Returned';
            return $row;
        });

        $cheques = $supplierCheques->concat($customerCheques)->sortBy('release_date')->values();

        $banks = ChequeBank::orderBy('bank_name')->get();

        return view('banking.bank_reconciliation', compact('cheques', 'banks', 'bankId', 'fromDate', 'toDate'));
    }

    /**
     * Commit every tick/untick made on screen in one go — nothing is saved
     * until this is called, so ticking a row is just a draft until you
     * press Save.
     */
    public function save(Request $request)
    {
        $request->validate([
            'changes'              => 'required|array|min:1',
            'changes.*.direction'  => 'required|in:supplier,customer',
            'changes.*.id'         => 'required|integer',
            'changes.*.reconciled' => 'required|boolean',
        ]);

        $updated = 0;

        foreach ($request->input('changes') as $change) {
            $model  = $change['direction'] === 'supplier' ? TSupCheque::class : TCusCheque::class;
            $cheque = $model::find($change['id']);

            if (!$cheque) {
                continue;
            }

            $cheque->is_reconciled = $change['reconciled'];
            $cheque->reconciled_at = $change['reconciled'] ? now() : null;
            $cheque->reconciled_by = $change['reconciled'] ? auth()->id() : null;
            $cheque->save();
            $updated++;
        }

        return response()->json([
            'status'  => 'success',
            'message' => "{$updated} cheque(s) updated.",
        ]);
    }
}
