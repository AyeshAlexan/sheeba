<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\THirePurchaseSum;

class HirepurchaseSumReportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = THirePurchaseSum::whereBetween('invoice_date', [$fromDate, $toDate])
                    ->where('is_cash_converted',0)
                    ->where('bc',$branch_code)
                    ->get();

        $query = THirePurchaseSum::whereBetween('invoice_date', [$fromDate, $toDate])
                    ->where('is_cash_converted',0)
                    ->where('bc',$branch_code)
                    ->get();

        if ($fromDate && $toDate){

            $query = THirePurchaseSum::whereBetween('invoice_date', [$fromDate, $toDate])
                    ->where('is_cash_converted',0)
                    ->where('bc',$branch_code)
                    ->get();
        }

        $sumDocumentAmount = $query->sum('document_charge');
        $totalDocumentAmount = number_format($sumDocumentAmount,2);

        $sumUnit =  $query->sum('down_payment');
        $totalUnit = number_format($sumUnit,2);

        $sumDiscount =  $query->sum('transport');
        $totalDiscount = number_format($sumDiscount,2);

        $sumInstallmentAmount =  $query->sum('instalment');
        $totalInstallmentAmount = number_format($sumInstallmentAmount,2);

        $sumCashPay =  $query->sum('no_of_instalment');
        $totalCashPay = number_format($sumCashPay,2);

        $sumCredite =  $query->sum('instalment');
        $totalCredite = number_format($sumCredite,2);

        $sumgross_amount =  $query->sum('gross_amount');
        $totalCheque = number_format($sumgross_amount,2);

        $sumdiscount =  $query->sum('discount');
        $totaldiscount = number_format($sumdiscount,2);

        $sumnet_amount =  $query->sum('net_amount');
        $totalnet_amount = number_format($sumnet_amount,2);

        $cash_payment =  $query->sum('cash_payment');
        $totalcash_payment = number_format($cash_payment,2);

        $final_gross_payment =  $query->sum('final_gross_amount');
        $final_gross_amount = number_format($final_gross_payment,2);

        $final_due_amount =  $query->sum('due_amount');
        $total_due_amount = number_format($final_due_amount,2);


        $TotalPawn = THirePurchaseSum::count();

        return view('HirepurchaseSumReport')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("document_charge", $totalDocumentAmount)
        -> with("down_payment", $totalUnit)
        -> with("transport", $totalDiscount)
        -> with("instalment_amount", $totalInstallmentAmount)
        -> with("no_of_instalment", $totalCashPay)
        -> with("instalment", $totalCredite)
        -> with("gross_amount", $totalCheque)
        -> with("discount", $totaldiscount)
        -> with("net_amount", $totalnet_amount)
        -> with("final_gross_amount", $final_gross_amount)
        -> with("total_due_amount", $total_due_amount)
        -> with("cash_payment", $totalcash_payment);

    }
}
