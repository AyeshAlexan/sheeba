<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\THirePurchaseDetails;

class HirepurchaseDetailsReportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = THirePurchaseDetails::whereBetween('invoice_date', [$fromDate, $toDate])
                    ->where('bc',$branch_code)
                    ->get();

        $query = THirePurchaseDetails::query();

        if ($fromDate && $toDate) {
            $query = THirePurchaseDetails::whereBetween('invoice_date', [$fromDate, $toDate])
                    ->where('bc',$branch_code)
                    ->get();

        }

        $sumGrossAmount = $query->sum('qty');
        $totalGrossAmount = number_format($sumGrossAmount,2);

        $sumUnit =  $query->sum('unit_price');
        $totalUnit = number_format($sumUnit,2);

        $sumDiscount =  $query->sum('discount');
        $totalDiscount = number_format($sumDiscount,2);

        $sumNetAmount =  $query->sum('net_value');
        $totalNetAmount = number_format($sumNetAmount,2);

        // $sumCashPay =  $query->sum('Cash_Pay');
        // $totalCashPay = number_format($sumCashPay,2);

        // $sumCredite =  $query->sum('Credite');
        // $totalCredite = number_format($sumCredite,2);

        // $sumCheque =  $query->sum('Cheque');
        // $totalCheque = number_format($sumCheque,2);

        $TotalPawn = THirePurchaseDetails::count();

        return view('reports.HirepurchaseDetailsReport')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("totalGrossAmount", $totalGrossAmount)
        -> with("totalUnit",)
        -> with("totalDiscount", $totalDiscount)
        -> with("totalNetAmount", $totalNetAmount);
        // -> with("totalCashPay", $totalCashPay)
        // -> with("totalCredite", $totalCredite)
        // -> with("totalCheque", $totalCheque);

    }
}
