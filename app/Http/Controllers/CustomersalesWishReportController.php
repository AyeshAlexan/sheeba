<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TInvoiceSum;
use App\Models\Customer;


class CustomersalesWishReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $Customer = $request->input('Customer');
        $branch_code = auth()->user()->BC;
    
        $invoice = TInvoiceSum::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('Customer_NIC',$Customer)
                    ->where('BC',$branch_code)
                    ->get();
    
        $query = TInvoiceSum::query();
    
        if ($fromDate && $toDate) {
            $query = TInvoiceSum::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('Customer_NIC',$Customer)
                    ->where('BC',$branch_code)
                    ->get();
    
        }
    
        $sumGrossAmount = $query->sum('Gross_Amount');
        $totalGrossAmount = number_format($sumGrossAmount,2);
    
        $sumDiscount =  $query->sum('Discount');
        $totalDiscount = number_format($sumDiscount,2);
    
        $sumNetAmount =  $query->sum('Net_Amount');
        $totalNetAmount = number_format($sumNetAmount,2);
    
        $sumCashPay =  $query->sum('Cash_Pay');
        $totalCashPay = number_format($sumCashPay,2);
    
        $sumCredite =  $query->sum('Credite');
        $totalCredite = number_format($sumCredite,2);
    
        $sumCheque =  $query->sum('Cheque');
        $totalCheque = number_format($sumCheque,2);
    
        $TotalPawn = TInvoiceSum::count();

        $Customer =Customer::all();
    
        return view('reports.customersalesWishReport')
        ->with("Customer", $Customer)
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("totalGrossAmount", $totalGrossAmount)
        -> with("totalDiscount", $totalDiscount)
        -> with("totalNetAmount", $totalNetAmount)
        -> with("totalCashPay", $totalCashPay)
        -> with("totalCredite", $totalCredite)
        -> with("totalCheque", $totalCheque);
    

        
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
