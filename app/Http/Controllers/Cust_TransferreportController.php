<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TCusSaleTrance;
use App\Models\Customer;
use App\Models\Company;
use App\Models\branchDel;

class Cust_TransferreportController extends Controller
{
   public function index(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $customerCode = $request->input('customer');
    $branchCode = auth()->user()->BC;

    // base query
    $query = TCusSaleTrance::where('BC', $branchCode);

    if ($fromDate && $toDate) {
        $query->whereBetween('dDate', [$fromDate, $toDate]);
    }

    if ($customerCode) {
        $query->where('customer', $customerCode);
        // ⚠️ Make sure "customer" matches the same column used in Blade
    }

    // clone for totals (so original query is not consumed)
    $totalsQuery = clone $query;

    $invoice = $query->get();

    $sumDrAmount = $totalsQuery->sum('dr_amount');
    $sumCrAmount = $totalsQuery->sum('cr_amount');

    $totalDrAmount = number_format($sumDrAmount, 2);
    $totalCrAmount = number_format($sumCrAmount, 2);
    $totalBalance = number_format($sumCrAmount - $sumDrAmount, 2);

    $Customerdata = Customer::all();

    return view('Cust_Transferreport')
        ->with("invoice", $invoice)
        ->with("Customerdata", $Customerdata)
        ->with("totalDrAmount", $totalDrAmount)
        ->with("totalCrAmount", $totalCrAmount)
        ->with("totalBalance", $totalBalance);
}

public function print(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $customerCode = $request->input('customer');
    $branchCode = auth()->user()->BC;

    $query = TCusSaleTrance::where('BC', $branchCode);

    if ($fromDate && $toDate) {
        $query->whereBetween('dDate', [$fromDate, $toDate]);
    }
    if ($customerCode) {
        $query->where('customer', $customerCode);
    }

    $totalsQuery = clone $query;
    $invoice = $query->get();

    $sumDrAmount = $totalsQuery->sum('dr_amount');
    $sumCrAmount = $totalsQuery->sum('cr_amount');

    return view('reports.print.customer-account', [
        'invoice'       => $invoice,
        'Customerdata'  => Customer::all(),
        'fromDate'      => $fromDate,
        'toDate'        => $toDate,
        'totalDrAmount' => number_format($sumDrAmount, 2),
        'totalCrAmount' => number_format($sumCrAmount, 2),
        'totalBalance'  => number_format($sumCrAmount - $sumDrAmount, 2),
        'companyData'   => Company::latest()->first(),
        'branchDel'     => branchDel::where('bccode', $branchCode)->first(),
    ]);
}

}