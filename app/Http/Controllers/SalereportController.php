<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TwithoutVatSalesSum;
use App\Models\TWithoutVatSalesDetails;
use App\Models\Customer;
use App\Models\Company;
use App\Models\branchDel;
use App\Models\TCusSaleTrance;
use Illuminate\Support\Facades\DB;


class SalereportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        $customer    = $request->input('customer');
        $salesman    = $request->input('salesman');
        $branch_code = auth()->user()->BC;

        $query = TwithoutVatSalesSum::where('BC', $branch_code)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Invoice_date', [$fromDate, $toDate]))
            ->when($customer, fn ($q) => $q->where('Customer_NIC', $customer))
            ->when($salesman, fn ($q) => $q->where('Salesmen', $salesman));

        $invoice = $query->get();

        $totalGrossAmount = number_format($invoice->sum('Gross_Amount'), 2);
        $totalDiscount    = number_format($invoice->sum('Discount'), 2);
        $totalNetAmount   = number_format($invoice->sum('Net_Amount'), 2);
        $totalCashPay     = number_format($invoice->sum('Cash_Pay'), 2);
        $totalCredite     = number_format($invoice->sum('Credite'), 2);
        $totalCheque      = number_format($invoice->sum('Cheque'), 2);

        return view('reports.sales_report', compact(
            'fromDate',
            'toDate',
            'customer',
            'salesman',
            'invoice',
            'totalGrossAmount',
            'totalDiscount',
            'totalNetAmount',
            'totalCashPay',
            'totalCredite',
            'totalCheque'
        ))->with([
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    public function printInvoice(Request $request)
    {
        $invoiceNo  = $request->query('invoice_no');
        $branchCode = $request->query('branch_code');
        $printType  = $request->query('print_type', 'normal');

        $companyData = Company::latest()->paginate(1);

        $T_detailsdata = TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
                            ->where('bc', $branchCode)
                            ->get();

        $T_sumdata = TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
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

        $invoiceRow = TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('bc', $branchCode)
            ->first();
        $isReprint = false;
        if ($invoiceRow) {
            $invoiceRow->increment('print_count');
            $invoiceRow->forceFill(['last_printed_at' => now()])->save();
            $isReprint = $invoiceRow->print_count > 1;
        }

        $view = $printType === 'pos'
            ? 'repairInvoiceWithoutPosPrint'
            : 'repairInvoiceWithoutPrint';

        return view($view, [
            'balance'         => $balance,
            'pawnSumData'     => $T_sumdata,
            'branchDel'       => $branchDel,
            'customerData'    => $T_customerdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData'     => $companyData,
            'isReprint'       => $isReprint,
        ]);
    }
    
    
    public function CustomerWishReport(Request $request)
    {
        $search = $request->input('customer_nic');

        // Make sure GROUP_CONCAT doesn't silently truncate long item lists
        DB::statement("SET SESSION group_concat_max_len = 10000");

        $query = DB::table('t_without_vat_sales_sums as s')
            ->join('t_without_vat_sales_details as d', 's.Invoice_no', '=', 'd.Invoice_no')
            ->leftJoin('customers as c', 'c.Code', '=', 's.Customer_NIC')
            ->select(
                's.Invoice_no',
                's.Customer_NIC',
                's.Customer_Name',
                DB::raw("GROUP_CONCAT(
                    CONCAT(d.Item_code, ' - ', d.Item_description, ' (x', d.QTY, ')')
                    SEPARATOR '||'
                ) as items_combined"),
                DB::raw('SUM(d.QTY) as total_qty')
            )
            ->groupBy('s.Invoice_no', 's.Customer_NIC', 's.Customer_Name');

        // Search across Code, First_name, and NIC
        if ($request->filled('customer_nic')) {
            $query->where(function ($q) use ($search) {
                $q->where('c.Code', 'LIKE', "%{$search}%")
                  ->orWhere('c.First_name', 'LIKE', "%{$search}%")
                  ->orWhere('s.Customer_NIC', 'LIKE', "%{$search}%");
            });
        }

        $reportData = $query->orderBy('s.Invoice_no')->get();

        return view('reports.customer_wish_report', [
            'reportData' => $reportData,
            'currentNic' => $search,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', auth()->user()->BC)->first(),
        ]);
    }
}