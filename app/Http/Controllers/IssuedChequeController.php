<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupCheque;
use App\Models\ChequeBank;
use Illuminate\Support\Facades\DB;

class IssuedChequeController extends Controller
{
    /**
     * List supplier cheques that have been recorded but not yet handed
     * over to the supplier ("PENDING") — the queue for the Issue action.
     */
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $query = TSupCheque::leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_sup_cheques.cheque_bank_id')
            ->where('t_sup_cheques.cheque_status', 'PENDING')
            ->select(
                't_sup_cheques.*',
                'cheque_banks.bank_name',
                'cheque_banks.branch as bank_branch',
                'cheque_banks.current_amount as bank_current_amount'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('t_sup_cheques.cheques_no', 'like', "%{$search}%")
                  ->orWhere('t_sup_cheques.supplier_name', 'like', "%{$search}%")
                  ->orWhere('t_sup_cheques.supplier_code', 'like', "%{$search}%")
                  ->orWhere('cheque_banks.bank_name', 'like', "%{$search}%");
            });
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('t_sup_cheques.release_date', [$fromDate, $toDate]);
        }

        $cheques = $query->orderBy('t_sup_cheques.release_date')->get();

        return view('banking.issued_cheques', compact('cheques', 'search', 'fromDate', 'toDate'));
    }

    /**
     * Bulk-mark the ticked cheques as actually issued to the supplier.
     * This is the moment the cheque's amount leaves the linked bank's balance.
     */
    public function markIssued(Request $request)
    {
        $request->validate([
            'cheque_ids'   => 'required|array|min:1',
            'cheque_ids.*' => 'integer|exists:t_sup_cheques,id',
        ]);

        DB::beginTransaction();
        try {
            $cheques = TSupCheque::whereIn('id', $request->cheque_ids)
                ->where('cheque_status', 'PENDING')
                ->get();

            if ($cheques->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'error',
                    'message' => 'None of the selected cheques are still pending — they may have already been issued.',
                ], 422);
            }

            $now = now();

            foreach ($cheques as $cheque) {
                $cheque->cheque_status    = 'ISSUED';
                $cheque->status_changed_at = $now;
                $cheque->save();

                if ($cheque->cheque_bank_id) {
                    ChequeBank::where('id', $cheque->cheque_bank_id)
                        ->decrement('current_amount', $cheque->amount);
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => count($cheques) . ' cheque(s) marked as issued.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
