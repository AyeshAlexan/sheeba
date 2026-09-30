<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TGentralReceipt;
use App\Models\MChartofAccount;
use Datatables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Amount

class GentralReceiptController extends Controller
{

    public function index()
    {
        if(request()->ajax()) {
            $query = TGentralReceipt::where('BC', auth()->user()->BC);

            if (request()->filled('from_date') && request()->filled('to_date')) {
                $query->whereBetween('date', [request('from_date'), request('to_date')]);
            }
            if (request()->filled('voucher_no')) {
                $query->where('invoice_no', request('voucher_no'));
            }
            if (request()->filled('account')) {
                $account = request('account');
                $query->where(function ($q) use ($account) {
                    $q->where('crcode', $account)->orWhere('drcode', $account);
                });
            }

            return datatables()->of($query->select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }



        $ChartAccount = MChartofAccount::all();
        $voucherNos = TGentralReceipt::where('BC', auth()->user()->BC)
            ->orderBy('invoice_no', 'desc')
            ->pluck('invoice_no')
            ->unique()
            ->values();

        return view('gentralreceipt')
        ->with("Amount", $ChartAccount)
        ->with("voucherNos", $voucherNos);
    }



public function addGentralReceipt(Request $request)
{
    DB::beginTransaction();

    try {
        $DepartmentId = $request->id;
        $actionDate   = Carbon::now();

        // ✅ Auto-generate invoice_no
        if ($DepartmentId) {
            // Updating existing record — keep the same invoice_no
            $invoice_no = TGentralReceipt::where('id', $DepartmentId)->value('invoice_no');
        } else {
            // New record — increment the last invoice_no
            $lastInvoice = TGentralReceipt::orderBy('invoice_no', 'desc')->first();
            $invoice_no  = $lastInvoice ? $lastInvoice->invoice_no + 1 : 1;
        }

        // ✅ Save or Update General Receipt
        $Department = TGentralReceipt::updateOrCreate(
            ['id' => $DepartmentId],
            [
                'invoice_no'  => $invoice_no,
                'date'        => $request->date,
                'cramount'    => $request->cramount,
                'crcode'      => $request->crcode,
                'dramount'    => $request->dramount,
                'drcode'      => $request->drcode,
                'description' => $request->description,
                'amount'      => $request->amount,
                'OC'          => auth()->user()->username, // ✅ use auth() consistently
                'BC'          => auth()->user()->BC,
            ]
        );

        // =============================================
        // ✅ ACCOUNTING ENTRY (t_account_trans)
        // =============================================

        // DR entry — only posted on new record creation, not on update
  if (!$DepartmentId) {
    // ✅ CR side — Credit the CR account (cash/bank received)
    DB::table('t_account_trans')->insert([
        'trance_type' => 'RECEIPT',
        'Ddate'       => $request->date,
        'AccCode'     => $request->crcode,   // CR Account
        'Description' => $request->description,
        'cr_amount'   => $request->amount,
        'dr_amount'   => 0,
        'action_date' => $actionDate,
        'oc'          => auth()->user()->username,
        'bc'          => auth()->user()->BC,
        'trance_no'   => $invoice_no,
        'no'          => $invoice_no,
        'created_at'  => $actionDate,
        'updated_at'  => $actionDate,
    ]);

    // ✅ DR side — Debit the DR account (income/revenue)
    DB::table('t_account_trans')->insert([
        'trance_type' => 'RECEIPT',
        'Ddate'       => $request->date,
        'AccCode'     => $request->drcode,   // DR Account
        'Description' => $request->description,
        'cr_amount'   => 0,
        'dr_amount'   => $request->amount,
        'action_date' => $actionDate,
        'oc'          => auth()->user()->username,
        'bc'          => auth()->user()->BC,
        'trance_no'   => $invoice_no,
        'no'          => $invoice_no,
        'created_at'  => $actionDate,
        'updated_at'  => $actionDate,
    ]);
}

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => $DepartmentId ? 'General receipt updated successfully.' : 'General receipt created successfully.',
            'data'    => $Department
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to save: ' . $e->getMessage()
        ], 500);
    }
}


    public function UpdateGentralReceipt(Request $request)
    {
        $where = array('id' => $request->id);
        $Department  = TGentralReceipt::where($where)->first();

        return Response()->json($Department);
    }


public function DeleteGentralReceipt(Request $request)
{
    DB::beginTransaction();

    try {
        $Department = TGentralReceipt::where('id', $request->id)->first();

        if ($Department) {
            // Delete related TAccountTrans records
            DB::table('t_account_trans')
                ->where('trance_no', $Department->invoice_no)
                ->where('trance_type', 'RECEIPT')
                ->where('bc', auth()->user()->BC)
                ->delete();

            // Delete the General Receipt
            $Department->delete();
        }

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'General receipt deleted successfully.',
            'data'    => $Department
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to delete: ' . $e->getMessage()
        ], 500);
    }
}



}