<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TInvoiceSum;
use App\Models\TSupplierPayment;
use App\Models\Suppliers;

class CashinoutController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branchCode = auth()->user()->BC;

        $query = TInvoiceSum::where('BC', $branchCode);
        $quer = TSupplierPayment::where('BC', $branchCode);

        if ($fromDate && $toDate) {
            $query->whereBetween('Invoice_date', [$fromDate, $toDate]) and
        $quer->whereBetween('Payment_date', [$fromDate, $toDate])
            ->get();
        }

        $invoice = $query->get();
        $sumCashPay = $query->sum('Cash_Pay');
        $totalCashPay = number_format($sumCashPay, 2);
        $invoic = $quer->get();
        $sumPayment_Amount = $quer->sum('Payment_Amount');
        $totalPayment_Amount = number_format($sumPayment_Amount, 2);

        $balance = $sumCashPay -  $sumPayment_Amount;
        $totalbalance = number_format( $balance, 2);

        $totalPawn = TInvoiceSum::count();
        $totalPayment = TSupplierPayment::count();
        return view('reports.Cash_in_out_report')
            ->with("invoice", $invoice)
            ->with("recipts", $query)
            ->with("recipt", $quer)
            ->with("totalPayment_Amount", $totalPayment_Amount)
            ->with("totalbalance", $totalbalance)
            ->with("totalCashPay", $totalCashPay);
    }
}
