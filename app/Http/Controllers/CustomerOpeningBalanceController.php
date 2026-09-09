<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCustomerBalace;
use App\Models\MChartofAccount;
use App\Models\TAccountTrans;
use App\Models\Customer;
use App\Models\TCusSaleTrance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Datatables;

class CustomerOpeningBalanceController extends Controller
{
    /* ─────────────────────────────────────────────────────────────
     |  INDEX  –  list + view
     |───────────────────────────────────────────────────────────── */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()
                ->of(TCustomerBalace::where('BC', auth()->user()->BC)->select('*'))
                ->addColumn('action', 'Action_button')   // blade partial for action buttons
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $maxCustomerNo  = TCustomerBalace::orderBy('invoice_no', 'desc')->value('invoice_no');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        $ChartAccount     = MChartofAccount::all();
        $customerdetails  = Customer::all();

        return view('customer_opening_balance')
            ->with('maxCustomer', $maxCustomerNos)
            ->with('Customer',    $customerdetails)
            ->with('Amount',      $ChartAccount);
    }

    /* ─────────────────────────────────────────────────────────────
     |  ADD / UPDATE  –  upsert balance + transaction
     |───────────────────────────────────────────────────────────── */
    public function addCustomerBalace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no'   => 'required|string',
            'customerName' => 'required|string',
            'customerCode' => 'required|exists:customers,Code',
            'amount'       => 'required|numeric|min:0',
            'date'         => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {

            $isEdit = !empty($request->id);

            /* 1. Upsert the opening balance record */
            $balance = TCustomerBalace::updateOrCreate(
                ['id' => $request->id],
                [
                    'invoice_no'   => $request->invoice_no,
                    'date'         => $request->date,
                    'customerCode' => $request->customerCode,
                    'customerName' => $request->customerName,
                    'description'  => $request->description,
                    'amount'       => $request->amount,
                    'OC'           => $request->OC,
                    'BC'           => $request->BC,
                ]
            );

            /* 2. Sync customer's Other_identifications field */
            Customer::where('Code', $request->customerCode)
                ->update(['Other_identifications' => $request->amount]);

            /* 3. Upsert the matching transaction in TCusSaleTrance */
            if ($isEdit) {
                // On edit: update the existing transaction row that matches this invoice
                TCusSaleTrance::where('trance_no', $request->invoice_no)
                    ->where('trance_type', 'CUS_OPEN_BC')
                    ->update([
                        'no'            => $request->invoice_no,
                        'customer'      => $request->customerCode,
                        'dr_trnce_code' => 'CUS_OPEN_BC',
                        'dr_trnce_no'   => $request->invoice_no,
                        'cr_amount'     => $request->amount,
                        'cr_trnce_code' => 'CUS_OPEN_BC',
                        'cr_trnce_no'   => $request->invoice_no,
                        'dr_amount'     => 0,
                        'trance_type'   => 'CUS_OPEN_BC',
                        'trance_no'     => $request->invoice_no,
                        'dDate'         => $request->date,
                        'bc'            => auth()->user()->BC,
                        'oc'            => auth()->user()->username,
                    ]);
            } else {
                // On create: insert a new transaction row
                TCusSaleTrance::create([
                    'no'            => $request->invoice_no,
                    'customer'      => $request->customerCode,
                    'dr_trnce_code' => 'CUS_OPEN_BC',
                    'dr_trnce_no'   => $request->invoice_no,
                    'cr_amount'     => $request->amount,
                    'cr_trnce_code' => 'CUS_OPEN_BC',
                    'cr_trnce_no'   => $request->invoice_no,
                    'dr_amount'     => 0,
                    'trance_type'   => 'CUS_OPEN_BC',
                    'trance_no'     => $request->invoice_no,
                    'dDate'         => $request->date,
                    'bc'            => auth()->user()->BC,
                    'oc'            => auth()->user()->username,
                ]);
            }

            return response()->json([
                'message' => $isEdit ? 'Balance updated successfully' : 'Balance added successfully',
                'data'    => $balance,
            ], 200);
        });
    }

    /* ─────────────────────────────────────────────────────────────
     |  FETCH FOR EDIT  –  return single row as JSON
     |───────────────────────────────────────────────────────────── */
    public function UpdateCustomerBalace(Request $request)
    {
        $record = TCustomerBalace::where('id', $request->id)->firstOrFail();
        return response()->json($record);
    }

    /* ─────────────────────────────────────────────────────────────
     |  DELETE  –  remove balance + matching transaction
     |───────────────────────────────────────────────────────────── */
    public function DeleteCustomerBalace(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $balance = TCustomerBalace::where('id', $request->id)->firstOrFail();

            // Remove the matching transaction from TCusSaleTrance
            TCusSaleTrance::where('trance_no',  $balance->invoice_no)
                           ->where('trance_type', 'CUS_OPEN_BC')
                           ->where('customer',    $balance->customerCode)
                           ->delete();

            // Remove the balance record itself
            $balance->delete();

            return response()->json(['message' => 'Record deleted successfully'], 200);
        });
    }

    /* ─────────────────────────────────────────────────────────────
     |  AJAX  –  get customer code by name
     |───────────────────────────────────────────────────────────── */
    public function GetCustomerCode(Request $request)
    {
        $data = Customer::where('First_name', $request->category)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }
}