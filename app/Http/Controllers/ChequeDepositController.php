<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\ChequeBank;
use App\Models\Customer;
use App\Models\TAccountTrans;
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
                't_cus_cheques.pending_amount',
                't_cus_cheques.trans_type',
                't_cus_cheques.cheque_bank_id'
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

        $chequeBanks = ChequeBank::where('is_active', true)->orderBy('bank_name')->get();

        return view('banking.cheque_deposit', compact('cheques', 'search', 'fromDate', 'toDate', 'chequeBanks'));
    }

    /**
     * Bulk-mark the ticked cheques as deposited/cleared at the bank.
     * Supplier cheques: status only (the balance already moved at Issue).
     * Customer cheques: status + the existing ledger posting to the customer account.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'supplier_ids'        => 'array',
            'supplier_ids.*'      => 'integer|exists:t_sup_cheques,id',
            'customer_ids'        => 'array',
            'customer_ids.*'      => 'integer|exists:t_cus_cheques,id',
            'account_for_cheque'  => 'array',
            'account_for_cheque.*' => 'nullable|integer|exists:cheque_banks,id',
        ]);

        $supplierIds       = $request->input('supplier_ids', []);
        $customerIds       = $request->input('customer_ids', []);
        $accountForCheque  = $request->input('account_for_cheque', []);

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
                // Opening Cheques never have a bank picked at entry time —
                // the account is chosen here, at deposit time, instead.
                if ($cheque->trans_type === 'OPENING_CHEQUE' && !$cheque->cheque_bank_id) {
                    $chosenAccountId = $accountForCheque[$cheque->id] ?? null;
                    if (!$chosenAccountId) {
                        DB::rollBack();
                        return response()->json([
                            'status'  => 'error',
                            'message' => "Pick an account to deposit opening cheque #{$cheque->cheques_no} into.",
                        ], 422);
                    }
                    $cheque->cheque_bank_id = $chosenAccountId;
                }

                $cheque->cheque_status     = 'deposit';
                $cheque->status_changed_at = $now;
                $cheque->save();

                if ($cheque->cheque_bank_id) {
                    ChequeBank::where('id', $cheque->cheque_bank_id)
                        ->increment('current_amount', $cheque->amount);

                    // The cheque's value moves out of "Cheques in Hand" (201-123)
                    // and into the bank the moment it's actually deposited — this
                    // was previously missing, leaving deposited cheques stuck
                    // showing as an asset forever. No customer-ledger entry here:
                    // the customer's balance already moved in full when the
                    // cheque was first recorded, not at deposit time.
                    TAccountTrans::create([
                        'trance_type' => 'CHEQUE_DEPOSIT',
                        'Ddate'       => $now->toDateString(),
                        'AccCode'     => '201-123',
                        'Description' => 'Cheque Deposited #' . $cheque->cheques_no,
                        'dr_amount'   => 0,
                        'cr_amount'   => $cheque->amount,
                        'trance_no'   => $cheque->id,
                        'no'          => $cheque->id,
                        'BC'          => $cheque->bc,
                        'OC'          => $cheque->oc,
                    ]);
                }

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
