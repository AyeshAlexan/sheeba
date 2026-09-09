<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\TCustomerPayment;
use App\Models\TInvoiceSum;
use App\Models\Customer;
use App\Models\TCusSaleTrance;
use App\Models\TCusCheque;
use App\Models\BankDetails;
use App\Models\BankBranch;

class CustomerPaymentController extends Controller
{
    //index
    public function index(){
        $Customer = Customer::all();
        $Banks = BankDetails::all();
        $Bank_branch = BankBranch::all();

        $credit_purchases = TInvoiceSum::whereNotNull('Credite')
                            ->get();

        //get credit_purchases
        // $credit_only = TCusSaleTrance::select('trance_no')
        //                     ->selectRaw('COUNT(*) as total_rows')
        //                     ->selectRaw('SUM(dr_amount) as total_dr_amount')
        //                     ->selectRaw('SUM(cr_amount) as total_cr_amount')
        //                     ->groupBy('trance_no')
        //                     ->havingRaw('total_dr_amount > 0')
        //                     ->havingRaw('total_cr_amount = 0')
        //                     ->get();

        // foreach ($credit_only as $row) {
        //     $credit_purchases1 = TInvoiceSum::where('Invoice_no', $row->trance_no)->get();
        //     $credit_purchases2[$row->trance_no] = $credit_purchases1;
        // }

        // $credit_purchases1 = TPurchasesSum::where('Invoice_no', $credit_only->trance_no)
        //                     ->get();

        $maxCustomerNo = TCustomerPayment::orderBy('Payment_no', 'desc')->value('Payment_no');
        $maxInvoiceNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('customer_payment')
        -> with("maxInvoiceNo", $maxInvoiceNos)
        -> with("credit_purchases", $credit_purchases)
        -> with("bank", $Banks)
        -> with("bank_branch", $Bank_branch)
        -> with("customer", $Customer);
    }

    //find Customer
    public function findCustomerPayment(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $supplyer_code = $request->search_string;

        $data = TInvoiceSum::where('Customer_NIC', $supplyer_code)
                            ->whereNotNull('Credite')
                            ->where('bc', $branch_code)
                            ->get();

        $purchase_total = $data->sum('credit_payment');
        // $install_total = number_format($inst_total,2);

        $amou_pay_total = $data->sum('amount_pay');
        // $amount_pay_total = number_format($amou_pay_total,2);

        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'purchase_total' => $purchase_total,
                'amount_pay_total' => $amou_pay_total,
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    //create payment
    public function create(Request $request){

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        // Decode the JSON string back to an array of objects
        $dataArray = json_decode($request->dataArray);

        $request->validate([
            'customer_code'=>'required',
            'customer_name'=>'required',
            'customer_phone'=>'required',
            'amount'=>'required',
            'payment_no'=>'required',
            'payment_date'=>'required',
            'sales_no'=>'required',
            'sales_date'=>'required',
        ]);

        $CustomerPayment = new TCustomerPayment;
        $CustomerPayment->Sales_no = $request->sales_no;
        $CustomerPayment->Payment_no = $request->payment_no;
        $CustomerPayment->Payment_date = $request->payment_date;
        $CustomerPayment->Customer_Code = $request->customer_code;
        $CustomerPayment->Customer_Name = $request->customer_name;
        $CustomerPayment->Customer_Phone = $request->customer_phone;
        $CustomerPayment->Payment_note = $request->payment_note;
        $CustomerPayment->Payment_Amount =  $request->amount;
        $CustomerPayment->cash_payment =  $request->cash_payment;
        $CustomerPayment->card_payment =  $request->card_payment;

        if($dataArray==!null){
            $CustomerPayment->cheque_payment =  $request->total_cheque_amount;
        }

        $CustomerPayment->bank_transfer =  $request->bank_transfer;
        $CustomerPayment->BC = auth()->user()->BC;
        $CustomerPayment->OC = auth()->user()->username;
        $CustomerPayment->save();


        $TCusSaleTrance = new TCusSaleTrance;
        $TCusSaleTrance->no = $request->sales_no;
        $TCusSaleTrance->customer = $request->customer_code;
        $TCusSaleTrance->dr_trnce_code="SALES";
        $TCusSaleTrance->dr_trnce_no = $request->sales_no;
        $TCusSaleTrance->dr_amount= $request->amount;
        $TCusSaleTrance->cr_trnce_code ="SALES";
        $TCusSaleTrance->cr_trnce_no = $request->sales_no;
        $TCusSaleTrance->cr_amount = "0";
        $TCusSaleTrance->trance_type ="SALES";
        $TCusSaleTrance->trance_no =  $request->sales_no;
        $TCusSaleTrance->dDate =  $request->payment_date;
        $TCusSaleTrance->BC = auth()->user()->BC;
        $TCusSaleTrance->OC = auth()->user()->username;
        $TCusSaleTrance->save();

        // Loop through each item in the array
        foreach ($dataArray as $value) {
            $supplyerCheque = new TCusCheque;
            $supplyerCheque->trans_no = $request->sales_no;
            $supplyerCheque->trans_type = 'RECEIPT';
            $supplyerCheque->bank = $value->bank_name;
            $supplyerCheque->branch_code = $value->bank_branch;
            $supplyerCheque->cheques_no = $value->cheque_no;
            $supplyerCheque->acc_no = $value->account_no;
            $supplyerCheque->release_date = $value->payment_date;
            $supplyerCheque->amount = $value->cheque_ammount;
            $supplyerCheque->oc = auth()->user()->username;
            $supplyerCheque->bc = auth()->user()->BC;
            $supplyerCheque->save();
        }

        TInvoiceSum::where('Invoice_no', $request->sales_no)
                    ->where('BC', $branch_code)
                    ->update(['Paid_Amount'=> $request->amount]);

        return response()->json([
            'status'=>'success',
        ]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
