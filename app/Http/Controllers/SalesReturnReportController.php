<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSalesReturnSum;
use App\Models\TSalesReturnDetails;   // ← must be here
use App\Models\Company;
use App\Models\Customer;
use App\Models\branchDel;
use App\Models\TCusSaleTrance;

class SalesReturnReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $query = TSalesReturnSum::where('BC', $branch_code);

        if ($fromDate && $toDate) {
            $query->whereBetween('Invoice_date', [$fromDate, $toDate]);
        }

        $invoice = $query->get();

        $totalGrossAmount = number_format($invoice->sum('Gross_Amount'), 2);
        $totalDiscount    = number_format($invoice->sum('Discount'),     2);
        $totalNetAmount   = number_format($invoice->sum('Net_Amount'),   2);
        $totalCashPay     = number_format($invoice->sum('Cash_Pay'),     2);
        $totalCredite     = number_format($invoice->sum('Credite'),      2);
        $totalCheque      = number_format($invoice->sum('Cheque'),       2);

        return view('reports.sales_return_report', compact(
            'fromDate', 'toDate', 'invoice',
            'totalGrossAmount', 'totalDiscount', 'totalNetAmount',
            'totalCashPay', 'totalCredite', 'totalCheque'
        ));
    }

public function getDetails($invoiceNo)
{
    $details = TSalesReturnDetails::where('Invoice_no', $invoiceNo)->get();
    return response()->json($details);
}

public function SalesReturnprintInvoice(Request $request)
    {
        $invoiceNo  = $request->query('invoice_no');
        $branchCode = $request->query('branch_code');
        $printType  = $request->query('print_type', 'normal');

        $companyData = Company::latest()->paginate(1);

        $T_detailsdata = TSalesReturnDetails::where('Invoice_no', $invoiceNo)
                            ->where('bc', $branchCode)
                            ->get();

        $T_sumdata = TSalesReturnSum::where('Invoice_no', $invoiceNo)
                        ->where('bc', $branchCode)
                        ->get();

        $customerNic    = $T_sumdata->first()?->Customer_NIC;
        $customerName   = $T_sumdata->first()?->Customer_Name;
        $T_customerdata = Customer::where('First_name', $customerName)->get();
        $branchDel      = branchDel::where('bccode', $branchCode)->get();

        $custData  = TCusSaleTrance::where('customer', $customerNic)->get();
        $dr_amount = $custData->sum('dr_amount');
        $cr_amount = $custData->sum('cr_amount');
        $balance   = $cr_amount - $dr_amount;

        $view = $printType === 'pos'
            ? 'repairInvoiceWithoutPosPrint'
            : 'repairReturnInvoicePrint';

        return view($view, [
            'balance'         => $balance,
            'pawnSumData'     => $T_sumdata,
            'branchDel'       => $branchDel,
            'customerData'    => $T_customerdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData'     => $companyData,
        ]);
    }
    public function show($id)                 { }
    public function edit($id)                 { }
    public function update(Request $request, $id) { }
    public function destroy($id)              { }
}