<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TPurchaseOrderSum;
use App\Models\Company;
use App\Models\branchDel;

class PurchaseorderreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TPurchaseOrderSum::when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
                    ->where('BC',$branch_code)
                    ->get();

        $query = $invoice;

        $sumGrossAmount = $query->sum('Gross_Amount');
        $totalGrossAmount = number_format($sumGrossAmount,2);

        $sumDiscount =  $query->sum('Discount');
        $totalDiscount = number_format($sumDiscount,2);

        $sumNetAmount =  $query->sum('Net_Amount');
        $totalNetAmount = number_format($sumNetAmount,2);

        // $sumCashPay =  $query->sum('Cash_Pay');
        // $totalCashPay = number_format($sumCashPay,2);

        // $sumCredite =  $query->sum('Credite');
        // $totalCredite = number_format($sumCredite,2);

        // $sumCheque =  $query->sum('Cheque');
        // $totalCheque = number_format($sumCheque,2);

        $TotalPawn = TPurchaseOrderSum::count();

        return view('reports.Purchase_order_report')
        -> with("invoice", $invoice)
        -> with("recipts", $query)
        -> with("totalGrossAmount", $totalGrossAmount)
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

        $invoice = TPurchaseOrderSum::when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
                    ->where('BC', $branch_code)
                    ->get();

        $totalGrossAmount = number_format($invoice->sum('Gross_Amount'), 2);
        $totalDiscount = number_format($invoice->sum('Discount'), 2);
        $totalNetAmount = number_format($invoice->sum('Net_Amount'), 2);

        return view('reports.print.purchase-order-summary', [
            'invoice' => $invoice,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalGrossAmount' => $totalGrossAmount,
            'totalDiscount' => $totalDiscount,
            'totalNetAmount' => $totalNetAmount,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }
}
