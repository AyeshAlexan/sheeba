<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TOpeningHirePurchaseSum;
use App\Models\Company;
use App\Models\branchDel;

class OpeningHirepurchaseSumReportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TOpeningHirePurchaseSum::when($fromDate && $toDate, fn ($q) => $q->whereBetween('invoice_date', [$fromDate, $toDate]))
                    ->where('bc',$branch_code)
                    ->get();

        $query = $invoice;

        $sumGrossAmount = $query->sum('document_charge');
        $totalGrossAmount = number_format($sumGrossAmount,2);

        $sumUnit =  $query->sum('down_payment');
        $totalUnit = number_format($sumUnit,2);

        $sumDiscount =  $query->sum('transport');
        $totalDiscount = number_format($sumDiscount,2);

        $sumNetAmount =  $query->sum('instalment_amount');
        $totalNetAmount = number_format($sumNetAmount,2);

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

        $TotalPawn = TOpeningHirePurchaseSum::count();

        return view('reports.OpeningHirepurchaseSumReport')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("document_charge", $totalGrossAmount)
        -> with("down_payment", $totalUnit)
        -> with("transport", $totalDiscount)
        -> with("instalment_amount", $totalNetAmount)
        -> with("no_of_instalment", $totalCashPay)
        -> with("instalment", $totalCredite)
        -> with("gross_amount", $totalCheque)
        -> with("discount", $totaldiscount)
        -> with("net_amount", $totalnet_amount)
        -> with("cash_payment", $totalcash_payment);

    }

    public function print(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TOpeningHirePurchaseSum::when($fromDate && $toDate, fn ($q) => $q->whereBetween('invoice_date', [$fromDate, $toDate]))
                    ->where('bc', $branch_code)
                    ->get();

        return view('reports.print.opening-hirepurchase-summary', [
            'invoice' => $invoice,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'document_charge' => number_format($invoice->sum('document_charge'), 2),
            'down_payment' => number_format($invoice->sum('down_payment'), 2),
            'transport' => number_format($invoice->sum('transport'), 2),
            'instalment_amount' => number_format($invoice->sum('instalment_amount'), 2),
            'no_of_instalment' => number_format($invoice->sum('no_of_instalment'), 2),
            'instalment' => number_format($invoice->sum('instalment'), 2),
            'gross_amount' => number_format($invoice->sum('gross_amount'), 2),
            'discount' => number_format($invoice->sum('discount'), 2),
            'net_amount' => number_format($invoice->sum('net_amount'), 2),
            'cash_payment' => number_format($invoice->sum('cash_payment'), 2),
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }
}
