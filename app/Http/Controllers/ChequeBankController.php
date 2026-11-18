<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChequeBank;
use App\Models\TAccountTrans;
use App\Models\MChartofAccount;
use App\Models\BankDetails;
use App\Models\BankBranch;
use Datatables;

class ChequeBankController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(ChequeBank::select('*'))
                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $toggleBtn = $row->is_active
                        ? '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleActive('.$row->id.')">Deactivate</button>'
                        : '<button type="button" class="btn btn-outline-success btn-sm" onclick="toggleActive('.$row->id.')">Activate</button>';
                    $statementBtn = '<a href="'.route('bank.statement', $row->id).'" class="btn btn-outline-primary btn-sm">Statement</a>';
                    return $statementBtn.' '.$toggleBtn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        $banks = BankDetails::orderBy('description')->get();

        return view('ChequeBanks', compact('banks'));
    }

    /**
     * Branches for one bank, filtered from the Bank Branch master — feeds
     * the dependent Branch dropdown on Add Account once a Bank is picked.
     */
    public function branchesFor(Request $request)
    {
        $branches = BankBranch::where('department', $request->bank_name)
            ->orderBy('category_description')
            ->pluck('category_description');

        return response()->json($branches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name'  => 'required',
            'account_no' => 'required',
        ]);

        $openingAmount = (float) ($request->opening_amount ?? 0);
        $chequeBank = ChequeBank::create([
            'bank_name'      => $request->bank_name,
            'branch'         => $request->branch,
            'account_no'     => $request->account_no,
            'opening_amount' => $openingAmount,
            'current_amount' => $openingAmount,
            'is_active'      => true,
            'OC'             => $request->OC,
            'BC'             => $request->BC,
        ]);

        // Each bank account gets its own GL code — it must never share
        // 201-123, which means "cheques physically held, not yet
        // deposited" everywhere else in the app (Opening Cheque, Cheque
        // Deposit/Return, customer/supplier cheque payments).
        $glCode = '202-' . str_pad($chequeBank->id, 3, '0', STR_PAD_LEFT);
        $chequeBank->update(['gl_code' => $glCode]);

        MChartofAccount::create([
            'account'         => 'Current Assets',
            'accountsub'      => 'Assets',
            'code'            => $glCode,
            'description'     => 'Bank — ' . $chequeBank->bank_name . ' (' . $chequeBank->account_no . ')',
            'opening_balance' => 0,
            'BC'              => $request->BC,
            'OC'              => $request->OC,
        ]);

        // Seed this bank's own GL account so the opening amount shows up
        // in Daily Transactions, without touching the Cheques account.
        if ($openingAmount > 0) {
            TAccountTrans::create([
                'trance_type' => 'BANK_OPEN',
                'Ddate'       => now()->format('Y-m-d'),
                'AccCode'     => $glCode,
                'Description' => 'Opening Amount — ' . $chequeBank->bank_name,
                'dr_amount'   => $openingAmount,
                'cr_amount'   => 0,
                'trance_no'   => $chequeBank->id,
                'no'          => $chequeBank->id,
                'BC'          => $request->BC,
                'OC'          => $request->OC,
            ]);
        }

        return response()->json($chequeBank);
    }

    /**
     * Toggle an account between active/inactive. Inactive accounts are
     * hidden from the Supplier/Customer Payment bank dropdowns but the
     * record and its balance history are kept intact — this replaces
     * edit/delete entirely for this master.
     */
    public function toggleActive(Request $request)
    {
        $chequeBank = ChequeBank::findOrFail($request->id);
        $chequeBank->is_active = ! $chequeBank->is_active;
        $chequeBank->save();

        return response()->json($chequeBank);
    }
}
