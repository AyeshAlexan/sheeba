<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TAccountTrans;

class CashTransferreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        $query = TAccountTrans::query();

        if ($fromDate && $toDate) {
            $query = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->get();

        }

        $sumDrAmount = $query->sum('dr_amount');
        $totalDrAmount = number_format($sumDrAmount,2);

        $sumCrAmount =  $query->sum('cr_amount');
        $totalCrAmount = number_format($sumCrAmount,2);

        // $sumDiscount =  $query->sum('Discount');
        // $totalDiscount = number_format($sumDiscount,2);

        // $sumNetAmount =  $query->sum('Net_value');
        // $totalNetAmount = number_format($sumNetAmount,2);
        
        $Balance = $sumCrAmount - $sumDrAmount;
        $totalBalance = number_format($Balance,2);

        $TotalPawn = TAccountTrans::count();

        return view('CashTransferreport')
        -> with("invoice", $invoice)
        -> with("fromDate", $fromDate)
        -> with("toDate", $toDate)
        -> with("recipts", $query)
        -> with("totalDrAmount", $totalDrAmount)
        -> with("totalBalance", $totalBalance)
        -> with("totalCrAmount", $totalCrAmount);

    }
}

