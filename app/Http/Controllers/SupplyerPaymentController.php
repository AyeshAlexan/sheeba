<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PDF;
use App\Models\TSupplierPayment;
use App\Models\TPurchasesSum;
use App\Models\Suppliers;
use App\Models\TSupPurchaseTrance;
use App\Models\TSupCheque;
use App\Models\BankDetails;
use App\Models\BankBranch;
use App\Models\ChequeBank;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class SupplyerPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Suppliers = Suppliers::all();
        $Banks = BankDetails::all();
        $Bank_branch = BankBranch::all();
        $ChequeBanks = ChequeBank::where('is_active', true)->get();

        $credit_purchases = TPurchasesSum::whereNotNull('credit_payment')
                            ->get();

        //get credit_purchases
        $credit_only = TSupPurchaseTrance::select('trance_no')
                            ->selectRaw('COUNT(*) as total_rows')
                            ->selectRaw('SUM(dr_amount) as total_dr_amount')
                            ->selectRaw('SUM(cr_amount) as total_cr_amount')
                            ->groupBy('trance_no')
                            ->havingRaw('total_dr_amount > 0')
                            ->havingRaw('total_cr_amount = 0')
                            ->get();

        foreach ($credit_only as $row) {
        // Fetch data from TPurchasesSum table for each trance_no
        $credit_purchases1 = TPurchasesSum::where('Invoice_no', $row->trance_no)->get();
        // Add the fetched data to the array using trance_no as key
        $credit_purchases2[$row->trance_no] = $credit_purchases1;
        }

        // $credit_purchases1 = TPurchasesSum::where('Invoice_no', $credit_only->trance_no)
        //                     ->get();

        $maxCustomerNo = TSupplierPayment::orderBy('Payment_no', 'desc')->value('Payment_no');
        $maxInvoiceNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('purchases_supplyer_payment')
        -> with("maxInvoiceNo", $maxInvoiceNos)
        -> with("credit_purchases", $credit_purchases)
        -> with("bank", $Banks)
        -> with("bank_branch", $Bank_branch)
        -> with("chequeBanks", $ChequeBanks)
        -> with("supplier", $Suppliers);
    }



public function findSupplierPayment(Request $request)
{
    $branch_code = auth()->user()->BC;
    $supplier_code = $request->search_string;

    // Get all supplier invoices with total paid amount
    $purchases = DB::table('t_purchases_sums as s')
        ->leftJoin('t_sup_purchase_trances as pay', function ($join) use ($branch_code) {
            $join->on('pay.no', '=', 's.Invoice_no')
                 ->where('pay.bc', $branch_code)
                 ->where('pay.trance_type', 'SUP_PAY');
        })
        ->leftJoin('suppliers', 'suppliers.Code', '=', 's.Customer_NIC')
        ->where('s.bc', $branch_code)
        ->where('s.Customer_NIC', $supplier_code)
        ->select(
            's.Invoice_no',
            's.Invoice_date',
            's.Customer_NIC',
            // Older purchase records were sometimes saved without a supplier
            // name/phone captured on the invoice itself — fall back to the
            // Suppliers master so payment validation doesn't dead-end on them.
            DB::raw('COALESCE(s.Customer_Name, suppliers.Name) as Customer_Name'),
            DB::raw('COALESCE(s.Customer_Phone, suppliers.Contact_1) as Customer_Phone'),
            's.Ref_no',
            's.credit_payment',
            DB::raw('COALESCE(SUM(pay.cr_amount), 0) as paid_amount')
        )
        ->groupBy(
            's.Invoice_no',
            's.Invoice_date',
            's.Customer_NIC',
            's.Customer_Name',
            's.Customer_Phone',
            'suppliers.Name',
            'suppliers.Contact_1',
            's.Ref_no',
            's.credit_payment'
        )
        ->get();

    if ($purchases->isEmpty()) {
        return response()->json(['status' => 'not_found']);
    }

    $result = $purchases->map(function($row) {
        $balance = $row->credit_payment - $row->paid_amount;
        return [
            'Customer_Name'  => $row->Customer_Name,
            'Invoice_no'     => $row->Invoice_no,
            'Invoice_date'   => $row->Invoice_date,
            'credit_payment' => $row->credit_payment,
            'paid_amount'    => $row->paid_amount,
            'Customer_Phone' => $row->Customer_Phone,
            'Customer_NIC'   => $row->Customer_NIC,
            'Ref_no'         => $row->Ref_no,
            'balance'        => $balance,
            'isPaid'         => $balance <= 0
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $result
    ]);
}


public function create(Request $request)
{
    $branch_code = auth()->user()->BC;
    $user_name = auth()->user()->username;

    // Decode cheque array
    $dataArray = json_decode($request->dataArray);

    // Validate request
    $request->validate([
        'supplier_code'   => 'required',
        'supplier_name'   => 'required',
        'supplier_phone'  => 'required',
        'amount'          => 'required|numeric',
        'payment_no'      => 'required',
        'payment_date'    => 'required|date',
        'purchse_no'      => 'required',
        'purchse_date'    => 'required|date',
    ]);

    // Blank/unused payment-method fields arrive as '' from the form, which
    // MySQL rejects for decimal columns — normalize them to 0 up front.
    $cashPayment    = is_numeric($request->cash_payment)    ? (float) $request->cash_payment    : 0;
    $cardPayment    = is_numeric($request->card_payment)    ? (float) $request->card_payment    : 0;
    $bankTransfer   = is_numeric($request->bank_transfer)   ? (float) $request->bank_transfer   : 0;
    $totalBalance   = is_numeric($request->totalBalance)    ? (float) $request->totalBalance    : 0;
    $chequePayment  = is_numeric($request->total_cheque_amount) ? (float) $request->total_cheque_amount : 0;

    DB::beginTransaction();
    try {

    // Save Supplier Payment
    $SupplierPayment = new TSupplierPayment;
    $SupplierPayment->Purchase_no    = $request->purchse_no;
    $SupplierPayment->Payment_no     = $request->payment_no;
    $SupplierPayment->Payment_date   = $request->payment_date;
    $SupplierPayment->Supplier_Code  = $request->supplier_code;
    $SupplierPayment->Supplier_Name  = $request->supplier_name;
    $SupplierPayment->Supplier_Phone = $request->supplier_phone;
    $SupplierPayment->Payment_note   = $request->payment_note;
    $SupplierPayment->Payment_Amount = $request->amount;
    $SupplierPayment->cash_payment   = $cashPayment;
    $SupplierPayment->card_payment   = $cardPayment;
    $SupplierPayment->totalBalance   = $totalBalance;

    if (!empty($dataArray)) {
        $SupplierPayment->cheque_payment = $chequePayment;
    }

    $SupplierPayment->bank_transfer = $bankTransfer;
    $SupplierPayment->BC            = $branch_code;
    $SupplierPayment->OC            = $user_name;
    $SupplierPayment->save();

    // Save Purchase Transaction
    $SupPurchaseTrance = new TSupPurchaseTrance;
    $SupPurchaseTrance->no            = $request->purchse_no;
    $SupPurchaseTrance->supplier      = $request->supplier_code;
    $SupPurchaseTrance->dr_trnce_code = 'SUP_PAY';
    $SupPurchaseTrance->dr_trnce_no   = $request->payment_no;
    $SupPurchaseTrance->dr_amount     = 0;
    $SupPurchaseTrance->cr_trnce_code = 'SUP_PAY';
    $SupPurchaseTrance->cr_trnce_no   = $request->payment_no;
    $SupPurchaseTrance->cr_amount     = $request->amount;
    $SupPurchaseTrance->trance_type   = 'SUP_PAY';
    $SupPurchaseTrance->trance_no     = $request->payment_no;
    $SupPurchaseTrance->dDate         = $request->payment_date;
    $SupPurchaseTrance->bc            = $branch_code;
    $SupPurchaseTrance->oc            = $user_name;
    $SupPurchaseTrance->save();

    // Work out what's still owed on this invoice after this payment, so
    // each cheque saved below can record whether it was a partial payment
    // and what remains pending — computed once, up front, from the same
    // figures used to update the invoice's paid_amount below.
    $purchaseInvoice   = TPurchasesSum::where('Invoice_no', $request->purchse_no)
                            ->where('BC', $branch_code)
                            ->first();
    $paid_amount       = $purchaseInvoice->paid_amount ?? 0;
    $totaAmount        = $paid_amount + $request->amount;
    $invoiceTotal      = $purchaseInvoice->credit_payment ?? null;
    $pendingAfterThis  = $invoiceTotal !== null ? round($invoiceTotal - $totaAmount, 2) : null;
    $isPartialPayment  = $pendingAfterThis !== null ? $pendingAfterThis > 0.01 : null;

    // Save Cheques
    if (!empty($dataArray)) {
        foreach ($dataArray as $value) {
            $supplyerCheque = new TSupCheque;
            $supplyerCheque->trans_no    = $request->purchse_no;
            $supplyerCheque->supplier_code = $request->supplier_code;
            $supplyerCheque->supplier_name = $request->supplier_name;
            $supplyerCheque->trans_type  = 'PURCHASE';
            $supplyerCheque->bank        = $value->bank_name;
            $supplyerCheque->cheque_bank_id = $value->cheque_bank_id ?? null;
            $supplyerCheque->cheque_status = 'PENDING';
            $supplyerCheque->cheques_no  = $value->cheque_no;
            $supplyerCheque->acc_no      = $value->account_no;
            $supplyerCheque->release_date= $value->cheque_date; // fixed
            $supplyerCheque->amount      = $value->cheque_ammount;
            $supplyerCheque->is_partial_payment = $isPartialPayment;
            $supplyerCheque->pending_amount     = $pendingAfterThis;
            $supplyerCheque->Payment_date  = $request->payment_date;
            $supplyerCheque->oc          = $user_name;
            $supplyerCheque->bc          = $branch_code;
            $supplyerCheque->save();
        }
    }

    // Update Paid Amount in Purchases

    TPurchasesSum::where('Invoice_no', $request->purchse_no)
        ->where('BC', $branch_code)
        ->update([
            'paid_amount' => $totaAmount,
        ]);

    // Get company data
    $companyData = Company::latest()->first();

    // Prepare PDF
    $payment_no = $request->payment_no;
    $SupplierPaymentData = TSupplierPayment::where('Payment_no', $payment_no)
                        ->where('BC', $branch_code)
                        ->get();

    $pdf = PDF::loadView('supplier_payment_print', [
        'SupplierPaymentData' => $SupplierPaymentData,
        'companyData' => $companyData
    ]);

    $pdfPath = public_path('assets/pdf/Supplier_Payment_Invoice.pdf');
    $pdf->save($pdfPath);

    DB::commit();

    return response()->json([
        'status' => 'success',
        'data'   => asset('assets/pdf/Supplier_Payment_Invoice.pdf'),
    ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status'  => 'error',
            'message' => 'Payment could not be saved: ' . $e->getMessage(),
        ], 500);
    }
}




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddSupplierpayment(Request $request)
    {
        $supp_code = $request->supplier_name;

        dd($supp_code);

        $request->validate([
            'supplier_code'=>'required | max:40 ',
            'supplier_name'=>'required | max:200 ',
            'supplier_phone'=>'required | max:200 ',
            'Payment_Amount'=>'max:200 ',
        ]);


        {
            $post = new TSupplierPayment;
            $post->Payment_no = $request->Payment_no;
            $post->Payment_date = $request->Payment_date;
            $post->Supplier_Code = $request->supplier_code;
            $post->Supplier_Name = $request->supplier_name;
            $post->Supplier_Phone = $request->supplier_phone;
            $post->Payment_note = $request->Payment_note;
            $post->Payment_Amount = $request->Payment_Amount;
            $post->OC= auth()->user()->username;
            $post->BC= auth()->user()->BC;
            $post->save();
            return redirect('purchases_supplyer_payment')->with('status', ' Supplier Payment Data Has Been inserted');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
