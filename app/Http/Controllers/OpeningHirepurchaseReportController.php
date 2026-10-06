<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TOpeningHirePurchaseDetails;
use App\Models\Company;
use App\Models\branchDel;

class OpeningHirepurchaseReportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TOpeningHirePurchaseDetails::when($fromDate && $toDate, fn ($q) => $q->whereBetween('invoice_date', [$fromDate, $toDate]))
                    ->where('bc',$branch_code)
                    ->get();

        $query = $invoice;

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

        $TotalPawn = TOpeningHirePurchaseDetails::count();

        return view('reports.OpeningHirepurchaseReport')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("totalGrossAmount", $totalGrossAmount)
        -> with("totalUnit", $totalUnit)
        -> with("totalDiscount", $totalDiscount)
        -> with("totalNetAmount", $totalNetAmount);
        // -> with("totalCashPay", $totalCashPay)
        // -> with("totalCredite", $totalCredite)
        // -> with("totalCheque", $totalCheque);

    }

    public function print(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TOpeningHirePurchaseDetails::when($fromDate && $toDate, fn ($q) => $q->whereBetween('invoice_date', [$fromDate, $toDate]))
                    ->where('bc', $branch_code)
                    ->get();

        $totalGrossAmount = number_format($invoice->sum('qty'), 2);
        $totalUnit = number_format($invoice->sum('unit_price'), 2);
        $totalNetAmount = number_format($invoice->sum('net_value'), 2);

        return view('reports.print.opening-hirepurchase-details', [
            'invoice' => $invoice,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalGrossAmount' => $totalGrossAmount,
            'totalUnit' => $totalUnit,
            'totalNetAmount' => $totalNetAmount,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }
}
