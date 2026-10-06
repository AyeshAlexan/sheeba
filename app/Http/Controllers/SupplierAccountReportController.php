<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TSupPurchaseTrance;
use App\Models\TSupplierPayment;
use App\Models\TSupCheque;
use App\Models\Suppliers;
use App\Models\Company;
use App\Models\branchDel;
use Illuminate\Support\Facades\DB;

class SupplierAccountReportController extends Controller
{
    public function index(Request $request){

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerCode = $request->input('supplier');
        $branchCode = auth()->user()->BC;

        $query = TSupPurchaseTrance::where('BC', $branchCode);

        if ($fromDate && $toDate) {
            $query->whereBetween('dDate', [$fromDate, $toDate]);
        }

        if ($customerCode) {
            $query->where('supplier', $customerCode);
        }

        $invoice = $query->get();

        $sumDrAmount = $query->sum('dr_amount');
        $totalDrAmount = number_format($sumDrAmount, 2);

        $sumCrAmount = $query->sum('cr_amount');
        $totalCrAmount = number_format($sumCrAmount, 2);

        $balance = $sumCrAmount - $sumDrAmount;
        $totalBalance = number_format($balance, 2);

        $totalPawn = TSupPurchaseTrance::count();

        return view('supplier_Report.supplier_account_report')
            ->with("invoice", $invoice)
            ->with("recipts", $query)
            ->with("totalDrAmount", $totalDrAmount)
            ->with("totalCrAmount", $totalCrAmount)
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("totalBalance", $totalBalance);

    }

    public function printAccount(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $supplierCode = $request->input('supplier');
        $branchCode = auth()->user()->BC;

        $query = TSupPurchaseTrance::where('BC', $branchCode);

        if ($fromDate && $toDate) {
            $query->whereBetween('dDate', [$fromDate, $toDate]);
        }

        if ($supplierCode) {
            $query->where('supplier', $supplierCode);
        }

        $invoice = $query->get();

        $totalDrAmount = number_format($invoice->sum('dr_amount'), 2);
        $totalCrAmount = number_format($invoice->sum('cr_amount'), 2);
        $totalBalance = number_format($invoice->sum('cr_amount') - $invoice->sum('dr_amount'), 2);

        return view('reports.print.supplier-account', [
            'invoice' => $invoice,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalDrAmount' => $totalDrAmount,
            'totalCrAmount' => $totalCrAmount,
            'totalBalance' => $totalBalance,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branchCode)->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function SupplierPaymentIndex(Request $request)
{
    $branch_code = auth()->user()->BC;

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $supplierCode = $request->input('supplier');

    $query = TSupplierPayment::where('BC', $branch_code)
        ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Payment_date', [$fromDate, $toDate]))
        ->when($supplierCode, fn ($q) => $q->where('Supplier_Code', $supplierCode));

    $payments = $query->orderBy('Payment_date', 'desc')->get();

    return view('supplier_Report.supplyer_payment_report', compact('payments'));
}

    public function printPayment(Request $request)
    {
        $branch_code = auth()->user()->BC;

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $supplierCode = $request->input('supplier');

        $payments = TSupplierPayment::where('BC', $branch_code)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween('Payment_date', [$fromDate, $toDate]))
            ->when($supplierCode, fn ($q) => $q->where('Supplier_Code', $supplierCode))
            ->orderBy('Payment_date', 'desc')
            ->get();

        return view('reports.print.supplier-payment', [
            'payments' => $payments,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SupplierBalanceIndex(Request $request)
    {

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $supplierCode = $request->input('supplier');
        $branchCode = auth()->user()->BC;
        $supName="";

        $query = TSupPurchaseTrance::select(
            't_sup_purchase_trances.supplier',
            DB::raw('SUM(t_sup_purchase_trances.dr_amount) as total_dr_amount'),
            DB::raw('SUM(t_sup_purchase_trances.cr_amount) as total_cr_amount'),
            'suppliers.Code',
            'suppliers.Name'
        )
        ->join('suppliers', 'suppliers.Code', '=', 't_sup_purchase_trances.supplier')
        ->groupBy('t_sup_purchase_trances.supplier', 'suppliers.Code', 'suppliers.Name');

        // ->get();
        // ->join('items', 't_invoice_deils.Item_code', '=', 'items.Item_code')
        // ->join('job_sheets', 't_invoice_sums.Job_no', '=', 'job_sheets.Job_no')
        // ->whereBetween('t_invoice_sums.Invoice_date', [$fromDate, $toDate])
        // ->groupBy('t_invoice_sums.Job_no')
        // $query = TSupPurchaseTrance::where('BC', $branchCode);

        if ($fromDate && $toDate && $supplierCode) {
            $query  ->whereBetween('dDate', [$fromDate, $toDate])
                    ->where('t_sup_purchase_trances.supplier',$supplierCode);
            $supName = $query->pluck('suppliers.Name')->first();
        }
        else if ($fromDate && $toDate) {
            $query  ->whereBetween('dDate', [$fromDate, $toDate]);
        }else if ($supplierCode) {
            $query  ->where('t_sup_purchase_trances.supplier',$supplierCode);
            $supName = $query->pluck('suppliers.Name')->first();
        }

        $supplier_details = $query->get();

       return view('supplier_Report.supplyer_balance_report')
        ->with("supplierData", $supplier_details)
        ->with("supName", $supName)
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
       ;
    }

    public function printBalance(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $supplierCode = $request->input('supplier');
        $branchCode = auth()->user()->BC;

        $query = TSupPurchaseTrance::select(
            't_sup_purchase_trances.supplier',
            DB::raw('SUM(t_sup_purchase_trances.dr_amount) as total_dr_amount'),
            DB::raw('SUM(t_sup_purchase_trances.cr_amount) as total_cr_amount'),
            'suppliers.Code',
            'suppliers.Name'
        )
        ->join('suppliers', 'suppliers.Code', '=', 't_sup_purchase_trances.supplier')
        ->groupBy('t_sup_purchase_trances.supplier', 'suppliers.Code', 'suppliers.Name');

        if ($fromDate && $toDate && $supplierCode) {
            $query->whereBetween('dDate', [$fromDate, $toDate])
                  ->where('t_sup_purchase_trances.supplier', $supplierCode);
        } elseif ($fromDate && $toDate) {
            $query->whereBetween('dDate', [$fromDate, $toDate]);
        } elseif ($supplierCode) {
            $query->where('t_sup_purchase_trances.supplier', $supplierCode);
        }

        $supplier_details = $query->get();

        return view('reports.print.supplier-balance', [
            'supplierData' => $supplier_details,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branchCode)->first(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
       public function IndexSupCheque(Request $request){
        $fromDate    = $request->input('from_date', '2000-01-01');
        $toDate      = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $query = TSupCheque::where('bc', $branch_code)
            ->when($toDate, fn ($q) => $q->whereBetween('release_date', [$fromDate, $toDate]));

        $receipts = $query->get();

        return view('supplier_Report.supplier_cheque_payment_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("receipts", $receipts);
    }

    public function printCheque(Request $request)
    {
        $fromDate = $request->input('from_date', '2000-01-01');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $receipts = TSupCheque::where('bc', $branch_code)
            ->when($toDate, fn ($q) => $q->whereBetween('release_date', [$fromDate, $toDate]))
            ->get();

        return view('reports.print.supplier-cheque-payment', [
            'receipts' => $receipts,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function supplierDetailsReportIndex(Request $request){
        $branch_code = auth()->user()->BC;
        $customers = Suppliers::all();

        $fromDate="";
        $toDate ="";

        return view('supplier_Report.supplier_details_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("customers", $customers);
    }

    public function printDetails(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customers = Suppliers::all();

        return view('reports.print.supplier-details', [
            'customers' => $customers,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
