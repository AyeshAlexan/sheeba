<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCustomerPayment;
use App\Models\TCusCheque;
use App\Models\Company;
use App\Models\branchDel;

class CustomerPaymentReportController extends Controller
{
    // Row-click detail popup: full payment breakdown + any cheques tied to it.
    // There's no direct FK from t_customer_payments to t_cus_cheques, but both
    // save flows (create()/makeTotalCreditPayment() in CustomerPaymentController)
    // write the same Sales_no to t_cus_cheques.trans_no, so matching on
    // trans_no + release_date + branch is the best correlation available.
    public function details(Request $request)
    {
        $branch_code = auth()->user()->BC;

        $payment = TCustomerPayment::where('id', $request->id)
            ->where('BC', $branch_code)
            ->first();

        if (!$payment) {
            return response()->json(['status' => 'not_found']);
        }

        $cheques = TCusCheque::where('trans_no', $payment->Sales_no)
            ->where('release_date', $payment->Payment_date)
            ->where('bc', $branch_code)
            ->get();

        return response()->json([
            'status'  => 'success',
            'payment' => $payment,
            'cheques' => $cheques,
        ]);
    }


    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $customer = $request->input('customer');
        $fromDate = "";
        $toDate = "";

        $SPayments = TCustomerPayment::where('BC', $branch_code)
            ->when($customer, fn ($q) => $q->where('Customer_Code', $customer))
            ->get();

        return view('reports.customer_payment_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("customer", $customer)
        ->with("paymentDetails", $SPayments);
    }

    public function filter(Request $request){
        $branch_code = auth()->user()->BC;

        $request->validate([
            'from_date'=>'required',
            'to_date'=>'required',
        ]);

        $fromDate =$request->from_date;
        $toDate = $request->to_date;
        $customer = $request->input('customer');

        $SPayments = TCustomerPayment::whereBetween('Payment_date', [$fromDate, $toDate])
                    ->where('BC', $branch_code)
                    ->when($customer, fn ($q) => $q->where('Customer_Code', $customer))
                    ->get();

        return view('reports.customer_payment_report')
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("customer", $customer)
            ->with("paymentDetails", $SPayments);
    }

    public function print(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customer = $request->input('customer');

        $SPayments = TCustomerPayment::where('BC', $branch_code)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Payment_date', [$fromDate, $toDate]))
            ->when($customer, fn ($q) => $q->where('Customer_Code', $customer))
            ->get();

        return view('reports.print.customer-payment', [
            'paymentDetails' => $SPayments,
            'fromDate'       => $fromDate,
            'toDate'         => $toDate,
            'companyData'    => Company::latest()->first(),
            'branchDel'      => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
