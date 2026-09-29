<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TPurchasesDetails;

class PurchasedetailreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TPurchasesDetails::when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
                    ->where('BC',$branch_code)
                    ->get();

        $query = $invoice;

        $itemCount = $query->sum('QTY');

       
        $sumUnit =  $query->sum('Unit_price');
        $totalUnit = number_format($sumUnit,2);

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

        $TotalPawn = TPurchasesDetails::count();

        return view('reports.Purchase_Detail_report')
        -> with("invoice", $invoice)
        -> with("totalItemCount", $itemCount)
        -> with("recipts", $query)
        // -> with("totalGrossAmount", $totalGrossAmount)
        -> with("totalUnit",$totalUnit)
        -> with("totalDiscount", $totalDiscount)
        -> with("totalNetAmount", $totalNetAmount);
        // -> with("totalCashPay", $totalCashPay)
        // -> with("totalCredite", $totalCredite)
        // -> with("totalCheque", $totalCheque);

    }
}
