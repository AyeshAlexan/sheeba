<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TWithoutVatSalesDetails;
use App\Models\Company;
use App\Models\branchDel;

class InvoicedetailreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TWithoutVatSalesDetails::when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
                    ->where('BC',$branch_code)
                    ->get();

        $query = $invoice;

        $sumQTY = $query->sum('QTY');
        $totalQTY= number_format($sumQTY,2);

        $sumGrossAmount =  $query->sum('Unit_price');
        $totalGrossAmount = number_format($sumGrossAmount,2);

        $sumDiscount =  $query->sum('Discount');
        $totalDiscount = number_format($sumDiscount,2);

        $sumNetAmount =  $query->sum('Net_value');
        $totalNetAmount = number_format($sumNetAmount,2);

        // $sumCashPay =  $query->sum('Cash_Pay');
        // $totalCashPay = number_format($sumCashPay,2);

        // $sumCredite =  $query->sum('Credite');
        // $totalCredite = number_format($sumCredite,2);

        // $sumCheque =  $query->sum('Cheque');
        // $totalCheque = number_format($sumCheque,2);

        $TotalPawn = TWithoutVatSalesDetails::count();

        return view('reports.Invoice_detail_report')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("totalQTY", $totalQTY)
        -> with("totalGrossAmount", $totalGrossAmount)
        -> with("totalDiscount", $totalDiscount)
        -> with("totalNetAmount", $totalNetAmount)
        -> with("companyData", Company::latest()->first())
        -> with("branchDel", branchDel::where('bccode', $branch_code)->first());
        // -> with("totalCashPay", $totalCashPay)
        // -> with("totalCredite", $totalCredite)
        // -> with("totalCheque", $totalCheque);

    }
}