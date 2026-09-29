<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TAdvancCusPayment;

class AdvancePaymentReportController extends Controller
{

    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TAdvancCusPayment::when($fromDate && $toDate, fn ($q) => $q->whereBetween('date', [$fromDate, $toDate]))
                    ->where('bc',$branch_code)
                    ->get();

        $query = $invoice;

        $sumGrossAmount = $query->sum('amount');
        $totalGrossAmount = number_format($sumGrossAmount,2);

        // $sumUnit =  $query->sum('down_payment');
        // $totalUnit = number_format($sumUnit,2);

        // $sumDiscount =  $query->sum('transport');
        // $totalDiscount = number_format($sumDiscount,2);

        // $sumNetAmount =  $query->sum('instalment_amount');
        // $totalNetAmount = number_format($sumNetAmount,2);

        // $sumCashPay =  $query->sum('no_of_instalment');
        // $totalCashPay = number_format($sumCashPay,2);

        // $sumCredite =  $query->sum('instalment');
        // $totalCredite = number_format($sumCredite,2);

        // $sumgross_amount =  $query->sum('gross_amount');
        // $totalCheque = number_format($sumgross_amount,2);

        // $sumdiscount =  $query->sum('discount');
        // $totaldiscount = number_format($sumdiscount,2);

        // $sumnet_amount =  $query->sum('net_amount');
        // $totalnet_amount = number_format($sumnet_amount,2);

        // $cash_payment =  $query->sum('cash_payment');
        // $totalcash_payment = number_format($cash_payment,2);

        // $TotalPawn = TAdvancCusPayment::count();

        return view('reports.AdvancePaymentReport')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
         -> with("amount", $totalGrossAmount);
        // -> with("down_payment", $totalUnit)
        // -> with("transport", $totalDiscount)
        // -> with("instalment_amount", $totalNetAmount)
        // -> with("no_of_instalment", $totalCashPay)
        // -> with("instalment", $totalCredite)
        // -> with("gross_amount", $totalCheque)
        // -> with("discount", $totaldiscount)
        // -> with("net_amount", $totalnet_amount)
        // -> with("cash_payment", $totalcash_payment);

    }
}
