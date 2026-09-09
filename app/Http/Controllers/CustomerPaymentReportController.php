<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCustomerPayment;

class CustomerPaymentReportController extends Controller
{

    public function index()
    {
        $branch_code = auth()->user()->BC;
        $SPayments = TCustomerPayment::where('BC', $branch_code)->get();

        $query = TCustomerPayment::query();
        $fromDate="";
        $toDate ="";

        return view('reports.customer_payment_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
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

        $SPayments = TCustomerPayment::whereBetween('Payment_date', [$fromDate, $toDate])
                    ->where('BC', $branch_code)
                    ->get();

        return view('reports.customer_payment_report')
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("paymentDetails", $SPayments);
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
