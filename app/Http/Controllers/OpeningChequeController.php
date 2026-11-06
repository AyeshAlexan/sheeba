<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCusCheque;
use App\Models\TAccountTrans;
use App\Models\TCusSaleTrance;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

/**
 * Historical customer cheques the business was already holding before
 * going live on this system — entered directly (no sales invoice behind
 * them). No company bank account is picked here; that only happens later
 * at Cheque Deposit time. Once saved they are ordinary PENDING rows in
 * t_cus_cheques, so they flow through the existing Cheque Deposit /
 * Cheque Return screens unchanged.
 */
class OpeningChequeController extends Controller
{
    private const ACC_CHEQUES_IN_HAND = '201-123';

    public function index(Request $request)
    {
        $cheques = TCusCheque::leftJoin('customers', 'customers.Code', '=', 't_cus_cheques.customer')
            ->leftJoin('cheque_banks', 'cheque_banks.id', '=', 't_cus_cheques.cheque_bank_id')
            ->where('t_cus_cheques.trans_type', 'OPENING_CHEQUE')
            ->select(
                't_cus_cheques.id',
                't_cus_cheques.customer as customer_code',
                'customers.First_name as customer_name',
                't_cus_cheques.cheques_no',
                't_cus_cheques.bank as drawee_bank',
                't_cus_cheques.acc_no as drawee_acc_no',
                'cheque_banks.bank_name as deposit_account',
                't_cus_cheques.release_date',
                't_cus_cheques.amount',
                't_cus_cheques.cheque_status'
            )
            ->orderByDesc('t_cus_cheques.id')
            ->get();

        $customers = Customer::orderBy('First_name')->get();

        return view('banking.opening_cheques', compact('cheques', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_code' => 'required|exists:customers,Code',
            'cheques_no'    => 'required|string',
            'amount'        => 'required|numeric|min:0.01',
            'release_date'  => 'required|date',
            'bank'          => 'nullable|string',
            'branch'        => 'nullable|string',
            'acc_no'        => 'nullable|string',
        ]);

        $branchCode = auth()->user()->BC;
        $userName   = auth()->user()->username;

        DB::beginTransaction();
        try {
            $amount = (float) $request->amount;

            $cheque               = new TCusCheque;
            $cheque->trans_type   = 'OPENING_CHEQUE';
            $cheque->trans_no     = $request->customer_code;
            $cheque->customer     = $request->customer_code;
            $cheque->bank         = $request->bank;
            $cheque->branch_code  = $request->branch;
            $cheque->acc_no       = $request->acc_no;
            $cheque->cheques_no   = $request->cheques_no;
            $cheque->amount       = $amount;
            $cheque->cheque_status = 'PENDING';
            $cheque->release_date = $request->release_date;
            $cheque->branch       = $branchCode;
            $cheque->oc           = $userName;
            $cheque->bc           = $branchCode;
            $cheque->save();

            // DR Cheques in Hand (asset up) / CR customer (what they owe goes down)
            TAccountTrans::create([
                'trance_type' => 'OPENING_CHEQUE',
                'Ddate'       => $request->release_date,
                'AccCode'     => self::ACC_CHEQUES_IN_HAND,
                'Description' => 'Opening Cheque — ' . $request->customer_code,
                'dr_amount'   => $amount,
                'cr_amount'   => 0,
                'trance_no'   => $cheque->id,
                'no'          => $cheque->id,
                'BC'          => $branchCode,
                'OC'          => $userName,
            ]);
            TAccountTrans::create([
                'trance_type' => 'OPENING_CHEQUE',
                'Ddate'       => $request->release_date,
                'AccCode'     => $request->customer_code,
                'Description' => 'Opening Cheque Adjustment',
                'dr_amount'   => 0,
                'cr_amount'   => $amount,
                'trance_no'   => $cheque->id,
                'no'          => $cheque->id,
                'BC'          => $branchCode,
                'OC'          => $userName,
            ]);

            TCusSaleTrance::create([
                'no'            => $cheque->id,
                'customer'      => $request->customer_code,
                'dr_trnce_code' => 'OPENING_CHEQUE',
                'dr_trnce_no'   => $cheque->id,
                'dr_amount'     => 0,
                'cr_trnce_code' => 'OPENING_CHEQUE',
                'cr_trnce_no'   => $cheque->id,
                'cr_amount'     => $amount,
                'trance_type'   => 'OPENING_CHEQUE',
                'trance_no'     => $cheque->id,
                'Display_Ref'   => $request->cheques_no,
                'dDate'         => $request->release_date,
                'bc'            => $branchCode,
                'oc'            => $userName,
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Opening cheque added.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $cheque = TCusCheque::where('id', $id)
                ->where('trans_type', 'OPENING_CHEQUE')
                ->first();

            if (!$cheque) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Opening cheque not found.'], 404);
            }

            if ($cheque->cheque_status !== 'PENDING') {
                DB::rollBack();
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This cheque has already been deposited or returned — it can no longer be removed here.',
                ], 422);
            }

            TAccountTrans::where('trance_type', 'OPENING_CHEQUE')->where('trance_no', $cheque->id)->delete();
            TCusSaleTrance::where('trance_type', 'OPENING_CHEQUE')->where('trance_no', $cheque->id)->delete();
            $cheque->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Opening cheque removed.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
