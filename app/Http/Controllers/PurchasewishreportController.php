<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TPurchasesDetails;

class PurchasewishreportController extends Controller

{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TPurchasesDetails::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        $query1 = TPurchasesDetails::query();

        $query = TPurchasesDetails::whereBetween('Invoice_date', [$fromDate, $toDate])
        ->where('BC',$branch_code)
        ->get();

        if ($fromDate && $toDate) {
            $query = TPurchasesDetails::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        }

        $itemCount = $query->sum('QTY');


        $sumUnit =  $query->sum('Unit_price');
        $totalUnit = number_format($sumUnit,2);

        // $sumDiscount =  $query->sum('Discount');
        // $totalDiscount = number_format($sumDiscount,2);

        $sumNetAmount =  $query->sum('Net_value');
        $totalNetAmount = number_format($sumNetAmount,2);

        $TotalPawn = TPurchasesDetails::count();

        return view('reports.Purchase_wish_sales_report')
        -> with("invoice", $invoice)
        -> with("totalItemCount", $itemCount)
        -> with("recipts", $query)
        -> with("totalUnit",$totalUnit)
        -> with("QTY", $itemCount)
        -> with("totalNetAmount", $totalNetAmount);


    }
}
