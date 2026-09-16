<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\TCusSaleTrance;
use App\Models\ChequeBank;
use Illuminate\Support\Facades\DB;

class ChequeReturnController extends Controller
{
    /**
     * Unified queue of cheques that can still bounce:
     * - supplier cheques that are ISSUED or already DEPOSITED (a cheque can
     *   bounce whether or not it's been formally marked deposited)
     * - customer cheques that are pending or already deposited
     */
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $supplierQuery = TSupCheque::leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_sup_cheques.cheque_bank_id')
            ->whereIn('t_sup_cheques.cheque_status', ['ISSUED', 'DEPOSITED'])
            ->select(
                't_sup_cheques.id',
                't_sup_cheques.cheques_no',
                't_sup_cheques.cheque_status',
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
            ->whereIn('t_cus_cheques.cheque_status', ['P', 'deposit'])
            ->select(
                't_cus_cheques.id',
                't_cus_cheques.cheques_no',
                't_cus_cheques.cheque_status',
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

        return view('banking.cheque_return', compact('cheques', 'search', 'fromDate', 'toDate'));
    }

    /**
     * Bulk-mark the ticked cheques as bounced/returned.
     * Supplier cheques: the amount goes back into the linked bank balance
     * (it was deducted at Issue, regardless of whether it's since been marked Deposited).
     * Customer cheques: if it had already been marked deposited (a ledger entry
     * reduced their balance), reverse that entry so the debt is reinstated.
     */
    public function returnCheque(Request $request)
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
                ->whereIn('cheque_status', ['ISSUED', 'DEPOSITED'])
                ->get();
            foreach ($supplierCheques as $cheque) {
                $cheque->cheque_status     = 'RETURNED';
                $cheque->status_changed_at = $now;
                $cheque->save();

                if ($cheque->cheque_bank_id) {
                    ChequeBank::where('id', $cheque->cheque_bank_id)
                        ->increment('current_amount', $cheque->amount);
                }
                $processed++;
            }

            $customerCheques = TCusCheque::whereIn('id', $customerIds)
                ->whereIn('cheque_status', ['P', 'deposit'])
                ->get();
            foreach ($customerCheques as $cheque) {
                $wasDeposited = $cheque->cheque_status === 'deposit';

                $cheque->cheque_status     = 'return';
                $cheque->status_changed_at = $now;
                $cheque->save();

                // The customer's debt was reduced in full when the payment was
                // recorded (regardless of deposit status), so a bounced cheque
                // always needs its ledger entry reversed to reinstate that debt.
                TCusSaleTrance::create([
                    'no'            => $cheque->trans_no,
                    'customer'      => $cheque->trans_no,
                    'dr_trnce_code' => null,
                    'dr_trnce_no'   => null,
                    'cr_trnce_code' => 'CHEQUE_RETURN',
                    'cr_trnce_no'   => $cheque->cheques_no,
                    'dr_amount'     => 0,
                    'cr_amount'     => $cheque->amount,
                    'bc'            => $cheque->bc,
                    'oc'            => $cheque->oc,
                    'trance_type'   => 'CHEQUE_RETURN',
                    'trance_no'     => $cheque->trans_no,
                    'Display_Ref'   => $cheque->cheques_no,
                    'dDate'         => $now->toDateString(),
                ]);

                // The bank balance was only increased if this cheque had
                // actually been deposited — only reverse it in that case.
                if ($wasDeposited && $cheque->cheque_bank_id) {
                    ChequeBank::where('id', $cheque->cheque_bank_id)
                        ->decrement('current_amount', $cheque->amount);
                }
                $processed++;
            }

            if ($processed === 0) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'error',
                    'message' => 'None of the selected cheques could be returned — they may have already been processed.',
                ], 422);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "{$processed} cheque(s) marked as returned.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
