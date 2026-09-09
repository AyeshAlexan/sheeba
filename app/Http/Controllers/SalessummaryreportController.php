<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TInvoiceDeils;

class SalessummaryreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TInvoiceDeils::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        $query = TInvoiceDeils::query();

        if ($fromDate && $toDate) {
            $query = TInvoiceDeils::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        }

        $sumGrossAmount = $query->sum('QTY');
        $totalGrossAmount = number_format($sumGrossAmount,2);

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

        $TotalPawn = TInvoiceDeils::count();

        return view('reports.Sales_summary_report')
            ->with("invoice", $invoice)
            ->with("recipts", $query)
            ->with("totalGrossAmount", $totalGrossAmount)
            ->with("totalUnit", $totalUnit)
            ->with("totalDiscount", $totalDiscount)
            ->with("totalNetAmount", $totalNetAmount);
        // ->with("totalCashPay", $totalCashPay)
        // ->with("totalCredite", $totalCredite)
        // ->with("totalCheque", $totalCheque);

    }
}