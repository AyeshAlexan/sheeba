<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChequeBank;
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
                    return $row->is_active
                        ? '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleActive('.$row->id.')">Deactivate</button>'
                        : '<button type="button" class="btn btn-outline-success btn-sm" onclick="toggleActive('.$row->id.')">Activate</button>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('ChequeBanks');
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
