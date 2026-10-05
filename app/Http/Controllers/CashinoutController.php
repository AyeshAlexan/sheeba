<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TInvoiceSum;
use App\Models\TSupplierPayment;
use App\Models\Suppliers;
use App\Models\Company;
use App\Models\branchDel;

class CashinoutController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customer = $request->input('customer');
        $supplier = $request->input('supplier');
        $branchCode = auth()->user()->BC;

        $query = TInvoiceSum::where('BC', $branchCode)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
            ->when($customer, fn ($q) => $q->where('Customer_NIC', $customer));

        $quer = TSupplierPayment::where('BC', $branchCode)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Payment_date', [$fromDate, $toDate]))
            ->when($supplier, fn ($q) => $q->where('Supplier_Code', $supplier));

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
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("customer", $customer)
            ->with("supplier", $supplier)
            ->with("totalPayment_Amount", $totalPayment_Amount)
            ->with("totalbalance", $totalbalance)
            ->with("totalCashPay", $totalCashPay)
            ->with("companyData", Company::latest()->first())
            ->with("branchDel", branchDel::where('bccode', $branchCode)->first());
    }
}
