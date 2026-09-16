<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\ChequeBank;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ChequeDepositController extends Controller
{
    /**
     * Unified queue of cheques ready to be banked:
     * - supplier cheques already ISSUED (handed over, waiting to clear)
     * - customer cheques received but not yet processed
     */
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $supplierQuery = TSupCheque::leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_sup_cheques.cheque_bank_id')
            ->where('t_sup_cheques.cheque_status', 'ISSUED')
            ->select(
                't_sup_cheques.id',
                't_sup_cheques.cheques_no',
                't_sup_cheques.supplier_name as party',
                't_sup_cheques.supplier_code as party_code',
                'cheque_banks.bank_name',
                't_sup_cheques.release_date',
                't_sup_cheques.amount',
                't_sup_cheques.is_partial_payment',
                't_sup_cheques.pending_amount'
            );

        if ($search) {
            $supplierQuery->where(function ($q) use ($search) {
                $q->where('t_sup_cheques.cheques_no', 'like', "%{$search}%")
                  ->orWhere('t_sup_cheques.supplier_name', 'like', "%{$search}%")
                  ->orWhere('cheque_banks.bank_name', 'like', "%{$search}%");
            });
        }

        if ($fromDate && $toDate) {
            $supplierQuery->whereBetween('t_sup_cheques.release_date', [$fromDate, $toDate]);
        }

        $supplierCheques = $supplierQuery->get()->map(function ($row) {
            $row->direction = 'supplier';
            return $row;
        });

        $customerQuery = TCusCheque::leftJoin('customers', 'customers.Code', '=', 't_cus_cheques.trans_no')
            ->whereNotIn('t_cus_cheques.cheque_status', ['deposit', 'return', 'ARCHIVED'])
            ->select(
                't_cus_cheques.id',
                't_cus_cheques.cheques_no',
                'customers.Name as party',
                't_cus_cheques.trans_no as party_code',
                't_cus_cheques.bank as bank_name',
                't_cus_cheques.release_date',
                't_cus_cheques.amount',
                't_cus_cheques.is_partial_payment',
                't_cus_cheques.pending_amount'
            );

        if ($search) {
            $customerQuery->where(function ($q) use ($search) {
                $q->where('t_cus_cheques.cheques_no', 'like', "%{$search}%")
                  ->orWhere('customers.Name', 'like', "%{$search}%")
                  ->orWhere('t_cus_cheques.bank', 'like', "%{$search}%");
            });
        }

        if ($fromDate && $toDate) {
            $customerQuery->whereBetween('t_cus_cheques.release_date', [$fromDate, $toDate]);
        }

        $customerCheques = $customerQuery->get()->map(function ($row) {
            $row->direction = 'customer';
            return $row;
        });

        $cheques = $supplierCheques->concat($customerCheques)->sortBy('release_date')->values();

        return view('banking.cheque_deposit', compact('cheques', 'search', 'fromDate', 'toDate'));
    }

    /**
     * Bulk-mark the ticked cheques as deposited/cleared at the bank.
     * Supplier cheques: status only (the balance already moved at Issue).
     * Customer cheques: status + the existing ledger posting to the customer account.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'supplier_ids'   => 'array',
            'supplier_ids.*' => 'integer|exists:t_sup_cheques,id',
            'customer_ids'   => 'array',
            'customer_ids.*' => 'integer|exists:t_cus_cheques,id',
        ]);

        $supplierIds = $request->input('supplier_ids', []);
        $customerIds = $request->input('customer_ids', []);

        if (empty($supplierIds) && empty($customerIds)) {
            return response()->json(['status' => 'error', 'message' => 'No cheques selected.'], 422);
        }

        DB::beginTransaction();
        try {
            $now = now();
            $processed = 0;

            $supplierCheques = TSupCheque::whereIn('id', $supplierIds)
                ->where('cheque_status', 'ISSUED')
                ->get();
            foreach ($supplierCheques as $cheque) {
                $cheque->cheque_status     = 'DEPOSITED';
                $cheque->status_changed_at = $now;
                $cheque->save();
                $processed++;
            }

            $customerCheques = TCusCheque::whereIn('id', $customerIds)
                ->whereNotIn('cheque_status', ['deposit', 'return', 'ARCHIVED'])
                ->get();
            foreach ($customerCheques as $cheque) {
                $cheque->cheque_status     = 'deposit';
                $cheque->status_changed_at = $now;
                $cheque->save();

                if ($cheque->cheque_bank_id) {
                    ChequeBank::where('id', $cheque->cheque_bank_id)
                        ->increment('current_amount', $cheque->amount);
                }

                // No customer-ledger entry here: the payment (including this
                // cheque's amount) was already posted to t_cus_sale_trances
                // in full when the payment was recorded — depositing only
                // updates the cheque's own status and the bank balance.
                $processed++;
            }

            if ($processed === 0) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'error',
                    'message' => 'None of the selected cheques could be deposited — they may have already been processed.',
                ], 422);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "{$processed} cheque(s) marked as deposited.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
