<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TCusCheque;
use App\Models\TCusSaleTrance;
use App\Models\Company;
use App\Models\branchDel;

class CustomerChequePaymentReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate   = $request->input('from_date', '2000-01-01');
        $toDate     = $request->input('to_date', now()->toDateString());
        $customer   = $request->input('customer');
        $branchCode = auth()->user()->BC;

        $receipts = TCusCheque::query()
            ->where('bc', $branchCode)
            ->whereBetween('release_date', [$fromDate, $toDate])
            ->when($customer, fn ($q) => $q->where('customer', $customer))
            ->orderBy('release_date')
            ->get();

        return view('reports.customer_cheque_payment_report', compact('receipts', 'fromDate', 'toDate', 'customer'));
    }

    public function print(Request $request)
    {
        $fromDate   = $request->input('from_date');
        $toDate     = $request->input('to_date');
        $customer   = $request->input('customer');
        $branchCode = auth()->user()->BC;

        $receipts = TCusCheque::query()
            ->where('bc', $branchCode)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('release_date', [$fromDate, $toDate]))
            ->when($customer, fn ($q) => $q->where('customer', $customer))
            ->orderBy('release_date')
            ->get();

        return view('reports.print.customer-cheque-payment', [
            'receipts'    => $receipts,
            'fromDate'    => $fromDate,
            'toDate'      => $toDate,
            'companyData' => Company::latest()->first(),
            'branchDel'   => branchDel::where('bccode', $branchCode)->first(),
        ]);
    }

    public function cashReceived(Request $request)
    {
        $request->validate([
            'cheque_id'   => 'required|exists:t_cus_cheques,id',
            'action_type' => 'required|in:deposit,return',
            'dr_amount'   => 'required_if:action_type,deposit|nullable|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            $cheque = TCusCheque::findOrFail($request->cheque_id);

            // Block double processing
            if (in_array($cheque->cheque_status, ['deposit', 'return'])) {
                return redirect()->back()
                    ->with('error', "Cheque {$cheque->cheques_no} has already been processed.");
            }

            // ── 1. Update cheque_status ──
            $cheque->cheque_status = $request->action_type; // 'deposit' or 'return'
            $cheque->save();

            // ── 2. Insert into t_cus_sale_trances only for deposit ──
            if ($request->action_type === 'deposit') {
                TCusSaleTrance::create([
                    'no'            => $cheque->trans_no,
                    'customer'      => $cheque->customer,
                    'dr_trnce_code' => 'CHEQUE',
                    'dr_trnce_no'   => $cheque->cheques_no,
                    'cr_trnce_code' => null,
                    'cr_trnce_no'   => null,
                    'dr_amount'     => $request->dr_amount,
                    'cr_amount'     => 0,
                    'bc'            => $cheque->bc,
                    'oc'            => $cheque->oc,
                    'trance_type'   => $cheque->trans_type,
                    'trance_no'     => $cheque->trans_no,
                    'Display_Ref'   => $cheque->cheques_no,
                    'dDate'         => now()->toDateString(),
                ]);
            }

            DB::commit();

            $label = $request->action_type === 'deposit' ? 'Deposited' : 'Returned';

            return redirect()->back()
                ->with('success', "Cheque {$cheque->cheques_no} successfully marked as {$label}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }
}