<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Category;
use App\Models\TwithoutVatSalesSum;
use App\Models\TWithoutVatSalesDetails;
use App\Models\TItemMovement;
use App\Models\TItemSerialMovement;
use App\Models\TAccountTrans;
use App\Models\TCusSaleTrance;
use App\Models\MRoute;
use App\Models\MSalesman;
use App\Models\branchDel;
use Illuminate\Support\Facades\DB;

class SalesInvoicewithoutVatController extends Controller
{
    // ════════════════════════════════════════════════════════════
    // INDEX — Show the create invoice page
    // ════════════════════════════════════════════════════════════
   public function index()
    {
        $branch_code = auth()->user()->BC;

        $maxInvoiceNo = TwithoutVatSalesSum::orderBy('Invoice_no', 'desc')
            ->where('BC', $branch_code)
            ->value('Invoice_no');

        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData    = Company::latest()->paginate(1);
        $itemCode       = Item::all();
        $itemCategory   = Category::all();
        $itemDetails    = Item::all();
        $salesman       = MSalesman::all();
        $area           = MRoute::all();
        $route          = MRoute::all();

        $maxCustomerNo  = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        $customerData = Customer::where('BC', $branch_code)->get();

        $currentDateTime  = now();
        $currentYear      = $currentDateTime->year;
        $currentMonth     = $currentDateTime->month;
        $nextAgreementNo  = $currentYear . '/' . $currentMonth . '/SEN/' . ($maxInvoice + 1);

        $stockDetails = Item::select(
            'items.Item_code',
            'items.Bar_code',
            'items.category',
            'items.Item_description',
            'items.purchasePrice',
            'items.saleprice',
            'items.Credit',
            DB::raw('COALESCE(SUM(t_item_movements.qun_in), 0) AS total_qun_in'),
            DB::raw('COALESCE(SUM(t_item_movements.qun_out), 0) AS total_qun_out'),
            DB::raw('COALESCE(SUM(t_item_movements.qun_in), 0) - COALESCE(SUM(t_item_movements.qun_out), 0) AS QTY'),
            DB::raw('MAX(t_item_movements.dDate) AS last_movement_date')
        )
        ->leftJoin('t_item_movements', function ($join) use ($branch_code) {
            $join->on('items.Item_code', '=', 't_item_movements.item_code');
        })
        ->groupBy(
            'items.Item_code',
            'items.Bar_code',
            'items.category',
            'items.Item_description',
            'items.purchasePrice',
            'items.saleprice',
            'items.Credit'
        )
        ->get();

        return view('salesInvoice_withoutVat')
            ->with('Route',           $route)
            ->with('nextAgreementNo', $nextAgreementNo)
            ->with('salesmandetails', $salesman)
            ->with('itemCategory',    $itemCategory)
            ->with('Customerdetails', $customerData)
            ->with('itemCode',        $itemCode)
            ->with('companyData',     $companyData)
            ->with('maxCustomer',     $maxCustomerNos)
            ->with('itemDetails',     $stockDetails)
            ->with('RouteDetails',    $route)
            ->with('area',            $area)
            ->with('maxInvoiceNo',    $maxInvoice);
    }

    // ════════════════════════════════════════════════════════════
    // STORE — Save a new invoice
    // ════════════════════════════════════════════════════════════
// ... other imports


// Ensure your models are imported at the top
// use App\Models\TwithoutVatSalesSum;
// ... etc

public function add_salesInvoice(Request $request)
{
    $request->validate([
        'inputs.*.invoice_no'   => 'required',
        'inputs.*.invoice_date' => 'required',
        'inputs.*.item_code'    => 'required',
        'inputs.*.qty'          => 'required',
        'inputs.*.unit_price'   => 'required',
        'inputs.*.net_value'    => 'required',
        'cash_payment'          => 'nullable|numeric|min:0',
        'half_payment'          => 'nullable|numeric|min:0',
        'credite_payment'       => 'nullable|numeric|min:0',
        'cheque_payment'        => 'nullable|numeric|min:0',
    ]);

    $netAmount = (float) $request->net_amount;
    $cashPayment = (float) ($request->cash_payment ?? 0);
    $halfPayment = (float) ($request->half_payment ?? 0);
    $chequePayment = (float) ($request->cheque_payment ?? 0);

    if ($cashPayment > 0 && $halfPayment > 0) {
        return back()->withInput()->with('error', 'Use either Cash Pay or Half Payment, not both.');
    }

    $creditPayment = round($netAmount - $cashPayment - $halfPayment - $chequePayment, 2);
    if ($creditPayment < 0) {
        return back()->withInput()->with('error', 'Payment amounts cannot be greater than the net amount.');
    }

    $branch_code = auth()->user()->BC;
    $oc          = auth()->user()->username;

    DB::beginTransaction();

    try {
        // ── 1. Save Summary ──────────────────────────────────────
        $InvoiceSum                   = new TwithoutVatSalesSum;
        $InvoiceSum->Invoice_no       = $request->invoice_no;
        $InvoiceSum->Invoice_date     = $request->invoice_date;
        $InvoiceSum->ref_no           = $request->ref_no;
        $InvoiceSum->customer_type    = $request->customer_type;
        $InvoiceSum->Customer_NIC     = $request->customer_nic;
        $InvoiceSum->Customer_Name    = $request->customer_name;
        $InvoiceSum->Customer_Phone   = $request->customer_phone;
        $InvoiceSum->Route            = $request->Route;
        $InvoiceSum->Salesmen         = $request->Salesmen;
        $InvoiceSum->Gross_Amount     = $request->gross_amount;
        $InvoiceSum->Discount         = $request->discount;
        $InvoiceSum->Net_Amount       = $request->net_amount;
        $InvoiceSum->customer_balance = $request->customer_balance;
        $InvoiceSum->Cash_Pay         = $cashPayment;
        $InvoiceSum->Half_Payment     = $halfPayment;
        $InvoiceSum->Credite          = $creditPayment;
        $InvoiceSum->Cheque           = $chequePayment;
        $InvoiceSum->invoice_remark           = $request->invoice_remark ?? 0;
        $InvoiceSum->BC               = $branch_code;
        $InvoiceSum->OC               = $oc;
        $InvoiceSum->save();

        // ── 2. Handle All Transactions (Account & Customer) ──────

        // A. Handle CHEQUE PAYMENT (2 Rows: DR & CR)
        if ($chequePayment > 0) {
            // Row 1: DR
            // Row 2: CR
            $this->insertAccountTrans($request, $branch_code, $oc, 0, $chequePayment, "Cheque Payment Adjustment");
            $this->insertCusTrance($request, $branch_code, $oc, 0, $chequePayment);
        }

        // B. Handle CREDIT PAYMENT (1 Row: CR)
        if ($creditPayment > 0) {
            $this->insertAccountTrans($request, $branch_code, $oc, 0, $creditPayment, "Credit Sale Entry");
            $this->insertCusTrance($request, $branch_code, $oc, 0, $creditPayment);
        }

        // C. Handle CASH PAYMENT (2 Rows: DR & CR)
        $cashReceived = $cashPayment + $halfPayment;
        if ($cashReceived > 0) {
            // Row 1: DR — hardcoded AccCode
            $this->insertAccountTrans($request, $branch_code, $oc, $cashReceived, 0, "Cash Payment Received", '201-001');
            $this->insertCusTrance($request, $branch_code, $oc, $cashReceived, 0);

            // Row 2: CR — normal customer_nic
            $this->insertAccountTrans($request, $branch_code, $oc, 0, $cashReceived, "Cash Payment Adjustment");
            $this->insertCusTrance($request, $branch_code, $oc, 0, $cashReceived);
        }

        // ── 3. Save Detail Rows + Stock Movements ────────────────
        foreach ($request->inputs as $value) {
            $detail                   = new TWithoutVatSalesDetails;
            $detail->Invoice_no       = $value['invoice_no'];
            $detail->Invoice_date     = $value['invoice_date'];
            $detail->Item_code        = $value['item_code'];
            $detail->Item_s_code      = $value['item_code'];
            $detail->Item_description = $value['item_description'];
            $detail->QTY              = $value['qty'];
            $detail->Per              = $value['Per'] ?? null;
            $detail->Unit_price       = $value['unit_price'];
            $detail->Discount         = $value['discount_val'];
            $detail->Net_value        = $value['net_value'];
            $detail->Free_Issues      = $value['Free_Issues'] ?? 0;
            $detail->Salesmen         = $request->Salesmen;
            $detail->OC               = $oc;
            $detail->BC               = $branch_code;
            $detail->save();

            // Look up the item master to check if this is a Set Item
            $itemMaster = DB::table('items')
                ->where('Item_code', $value['item_code'])
                ->first();

            // 3a. Movement for the main/sold item itself — always goes out
            $movement              = new TItemMovement;
            $movement->trans_no    = $value['invoice_no'];
            $movement->dDate       = $value['invoice_date'];
            $movement->trans_code  = 'SALES_OUT_VAT';
            $movement->item_code   = $value['item_code'];
            $movement->qun_out     = $value['qty'];
            $movement->Free_Issues = $value['Free_Issues'] ?? 0;
            $movement->bc          = $branch_code;
            $movement->save();

            // 3b. If it's a Set Item, also pass out every sub-item's stock
            if ($itemMaster && $itemMaster->category === 'Set Item') {
                $packageItems = DB::table('package_items')
                    ->where('pkg_code', $value['item_code'])
                    ->get();

                foreach ($packageItems as $pkgItem) {
                    $subMovement              = new TItemMovement;
                    $subMovement->trans_no    = $value['invoice_no'];
                    $subMovement->dDate       = $value['invoice_date'];
                    $subMovement->trans_code  = 'SALES_OUT_VAT';
                    $subMovement->item_code   = $pkgItem->item_code;
                    $subMovement->qun_out     = $pkgItem->qty * $value['qty']; // scale by qty sold
                    $subMovement->Free_Issues = 0;
                    $subMovement->bc          = $branch_code;
                    $subMovement->save();
                }
            }
        }

        // ── 4. Save Cheque Details ───────────────────────────────
        $chequesJson = $request->input('cheques_data');
        if ($chequesJson) {
            $cheques = json_decode($chequesJson, true);
            if (is_array($cheques)) {
                foreach ($cheques as $index => $cheque) {
                    DB::table('t_cus_cheques')->insert([
                        'trans_type'     => 'SALES_OUT_VAT',
                        'trans_no'       => $request->invoice_no,
                        'customer'       => $request->customer_nic,
                        'bank'           => $cheque['bank']       ?? null,
                        'branch_code'    => $cheque['branch']     ?? null,
                        'cheques_no'     => $cheque['chequeNo']   ?? null,
                        'trans_order_no' => $index + 1,
                        'acc_no'         => $cheque['accNo']      ?? null,
                        'amount'         => $cheque['amount']     ?? 0,
                        'cheque_status'  => 'PENDING',
                        'release_date'   => $cheque['releaseDate'] ?? null,
                        'branch'         => $branch_code,
                        'oc'             => $oc,
                        'bc'             => $branch_code,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        }

        DB::commit();

        return back()
            ->with('done', 'Invoice added successfully')
            ->with('invoice_no', $request->invoice_no)
            ->with('print_url', route('sales.invoice.print', [
                'invoice_no'  => $request->invoice_no,
                'branch_code' => $branch_code,
                'print_type'  => 'normal',
            ]))
            ->with('pos_print_url', route('sales.invoice.print', [
                'invoice_no'  => $request->invoice_no,
                'branch_code' => $branch_code,
                'print_type'  => 'pos',
            ]));

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

/**
 * Helper: Record to TAccountTrans
 */
private function insertAccountTrans($request, $bc, $oc, $dr, $cr, $desc, $accCode = null)
{
    $at               = new TAccountTrans;
    $at->trance_type  = 'SALES_OUT_VAT';
    $at->Ddate        = $request->invoice_date;
    $at->AccCode      = $accCode ?? $request->customer_nic; // ← uses override if provided
    $at->Description  = $desc;
    $at->dr_amount    = $dr;
    $at->cr_amount    = $cr;
    $at->trance_no    = $request->invoice_no;
    $at->no           = $request->invoice_no;
    $at->BC           = $bc;
    $at->OC           = $oc;
    $at->save();
}


/**
 * Helper: Record to TCusSaleTrance
 */
private function insertCusTrance($request, $bc, $oc, $dr, $cr)
{
    $ct               = new TCusSaleTrance;
    $ct->no            = $request->invoice_no;
    $ct->customer      = $request->customer_nic;
    $ct->dr_trnce_code = 'SALES_OUT_VAT';
    $ct->dr_trnce_no   = $request->invoice_no;
    $ct->cr_trnce_code = 'SALES_OUT_VAT';
    $ct->cr_trnce_no   = $request->invoice_no;
    $ct->dr_amount     = $dr;
    $ct->cr_amount     = $cr;
    $ct->trance_type   = 'SALES_OUT_VAT';
    $ct->trance_no     = $request->invoice_no;
    $ct->dDate         = $request->invoice_date;
    $ct->BC            = $bc;
    $ct->OC            = $oc;
    $ct->save();
}

    // ════════════════════════════════════════════════════════════
    // PRINT — Print Invoice (new method via route)
    // ════════════════════════════════════════════════════════════
public function printInvoice(Request $request)
{
    $invoiceNo  = $request->invoice_no;
    $branchCode = $request->branch_code;
    $printType  = $request->print_type ?? 'normal';

    $companyData    = Company::latest()->paginate(1);

    $T_detailsdata  = TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
                        ->where('bc', $branchCode)   // ← lowercase bc
                        ->get();

    $T_sumdata      = TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
                        ->where('bc', $branchCode)   // ← lowercase bc
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
        : 'repairInvoiceWithoutPrint';

    return view($view, [
        'balance'         => $balance,
        'pawnSumData'     => $T_sumdata,
        'branchDel'       => $branchDel,
        'customerData'    => $T_customerdata,
        'pawnDetailsData' => $T_detailsdata,
        'companyData'     => $companyData,
    ]);
}

    // ════════════════════════════════════════════════════════════
    // PRINT — Ajax print existing invoice (reprint)
    // ════════════════════════════════════════════════════════════
    public function printwithoutvat(Request $request)
    {
        $invoiceNo   = $request->sales_invoice_no;
        $branch_code = auth()->user()->BC;
        $companyData = Company::latest()->paginate(1);

        $T_detailsdata = TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
            ->where('bc', $branch_code)->get();
        $T_sumdata = TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('bc', $branch_code)->get();

        $customer_nic = TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('bc', $branch_code)
            ->pluck('Customer_NIC')->first();

        $T_customerdata = Customer::where('Code', $customer_nic)->get();

        $custData  = TCusSaleTrance::where('customer', $customer_nic)->get();
        $dr_amount = $custData->sum('dr_amount');
        $cr_amount = $custData->sum('cr_amount');
        $balance   = $cr_amount - $dr_amount;

        if ($T_sumdata->count()) {
            $pdf = PDF::loadView('repairInvoiceWithoutRePrint', [
                'balance'         => $balance,
                'pawnSumData'     => $T_sumdata,
                'customerData'    => $T_customerdata,
                'pawnDetailsData' => $T_detailsdata,
                'companyData'     => $companyData,
            ]);

            $pdfPath = storage_path('../public/assets/pdf/Sales_Reprint_Invoice.pdf');
            $pdf->save($pdfPath);
            $pdfUrl = asset('/public/assets/pdf/Sales_Reprint_Invoice.pdf');

            return response()->json(['status' => 'success', 'pdfUrl' => $pdfUrl]);
        }

        return response()->json(['status' => 'not_found']);
    }

    // ════════════════════════════════════════════════════════════
    // FIND INVOICE DETAILS (JSON) — used by Recall
    // ════════════════════════════════════════════════════════════
    public function FindInvoicewithoutVatdetails(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $receiptNo   = $request->search_receipt_no;

        $data = TWithoutVatSalesDetails::where('Invoice_no', $receiptNo)
            ->where('BC', $branch_code)
            ->get();

        if ($data->count()) {
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        return response()->json(['status' => 'not_found']);
    }

    // ════════════════════════════════════════════════════════════
    // FIND INVOICE SUMMARY (JSON) — used by Recall
    // ════════════════════════════════════════════════════════════
    public function FindInvoicewithoutVatSum(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $receiptNo   = $request->search_receipt_no;

        $data = TwithoutVatSalesSum::where('Invoice_no', $receiptNo)
            ->where('BC', $branch_code)
            ->get();

        if ($data->count()) {
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        return response()->json(['status' => 'not_found']);
    }

    // ════════════════════════════════════════════════════════════
    // UPDATE — Edit recalled invoice (add new + update existing items)
    // ════════════════════════════════════════════════════════════
    public function updateSalesInvoice(Request $request)
    {
        $invoice     = $request->input('invoice');
        $branch_code = auth()->user()->BC;
        $oc          = auth()->user()->username;

        $invoiceNo   = $invoice['invoice_no'];
        $invoiceDate = $invoice['invoice_date'];
        $customerNic = $invoice['customer_nic'];
        $items       = $invoice['items'] ?? [];

        // ── 1. Update Summary Header ──────────────────────────
        TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)
            ->update([
                'Invoice_date'  => $invoiceDate,
                'Customer_NIC'  => $customerNic,
                'Customer_Name' => $invoice['customer_name'],
                'Route'         => $invoice['Route'],
                'Salesmen'      => $invoice['Salesmen'],
                'Gross_Amount'  => $invoice['total_amount'],
                'Discount'      => $invoice['paid_discount'],
                'Net_Amount'    => $invoice['paid_amount'],
                'Cash_Pay'      => $invoice['cash_payment'],
                'Credite'       => $invoice['credite_payment'],
                'Cheque'        => $invoice['cheque_payment'],
                'OC'            => $oc,
            ]);

        // ── 2. Update Account Transaction ─────────────────────
        TAccountTrans::where('trance_no',   $invoiceNo)
            ->where('trance_type', 'SALES_OUT_VAT')
            ->where('BC',          $branch_code)
            ->update([
                'Ddate'     => $invoiceDate,
                'AccCode'   => $customerNic,
                'cr_amount' => $invoice['cash_payment'],
                'OC'        => $oc,
            ]);

        // ── 3. Rebuild Customer Sale Transactions ─────────────
        TCusSaleTrance::where('trance_no',   $invoiceNo)
            ->where('trance_type', 'SALES_OUT_VAT')
            ->where('BC',          $branch_code)
            ->delete();

        $paymentAmount = 0;
        if ($invoice['cash_payment']     > 0) $paymentAmount = $invoice['cash_payment'];
        elseif ($invoice['credite_payment'] > 0) $paymentAmount = $invoice['credite_payment'];
        elseif ($invoice['cheque_payment']  > 0) $paymentAmount = $invoice['cheque_payment'];

        $cust1                = new TCusSaleTrance;
        $cust1->no            = $invoiceNo;
        $cust1->customer      = $customerNic;
        $cust1->dr_trnce_code = 'SALES_OUT_VAT';
        $cust1->dr_trnce_no   = $invoiceNo;
        $cust1->cr_trnce_code = 'SALES_OUT_VAT';
        $cust1->cr_trnce_no   = $invoiceNo;
        $cust1->dr_amount     = 0;
        $cust1->cr_amount     = $paymentAmount;
        $cust1->trance_type   = 'SALES_OUT_VAT';
        $cust1->trance_no     = $invoiceNo;
        $cust1->dDate         = $invoiceDate;
        $cust1->BC            = $branch_code;
        $cust1->OC            = $oc;
        $cust1->save();

        if ($invoice['cash_payment'] > 0 || $invoice['cheque_payment'] > 0) {
            $cust2                = new TCusSaleTrance;
            $cust2->no            = $invoiceNo;
            $cust2->customer      = $customerNic;
            $cust2->dr_trnce_code = 'SALES_OUT_VAT';
            $cust2->dr_trnce_no   = $invoiceNo;
            $cust2->cr_trnce_code = 'SALES_OUT_VAT';
            $cust2->cr_trnce_no   = $invoiceNo;
            $cust2->dr_amount     = $invoice['cash_payment'] > 0
                ? $invoice['cash_payment']
                : $invoice['cheque_payment'];
            $cust2->cr_amount     = 0;
            $cust2->trance_type   = 'SALES_OUT_VAT';
            $cust2->trance_no     = $invoiceNo;
            $cust2->dDate         = $invoiceDate;
            $cust2->BC            = $branch_code;
            $cust2->OC            = $oc;
            $cust2->save();
        }

        // ── 4. Process each item: NEW vs EXISTING ─────────────
        foreach ($items as $item) {
            $isNew = isset($item['is_new']) && $item['is_new'] == true;

            if ($isNew) {
                // INSERT new detail row
                $detail                       = new TWithoutVatSalesDetails;
                $detail->Invoice_no           = $invoiceNo;
                $detail->Invoice_date         = $invoiceDate;
                $detail->Item_code            = $item['Item_code'];
                $detail->Item_s_code          = $item['Item_code'];
                $detail->Item_description     = $item['Item_description'];
                $detail->QTY                  = $item['QTY'];
                $detail->Unit_price           = $item['Unit_price'];
                $detail->Free_Issues          = $item['Free_Issues'];
                $detail->DiscountPercentage   = $item['DiscountPercentage'] ?? 0;
                $detail->Discount             = $item['Discount'];
                $detail->Net_value            = $item['Net_value'];
                $detail->Salesmen             = $invoice['Salesmen'];
                $detail->OC                   = $oc;
                $detail->BC                   = $branch_code;
                $detail->save();

                // INSERT new stock movement
                $movement              = new TItemMovement;
                $movement->trans_no    = $invoiceNo;
                $movement->dDate       = $invoiceDate;
                $movement->trans_code  = 'SALES_OUT_VAT';
                $movement->item_code   = $item['Item_code'];
                $movement->qun_out     = $item['QTY'];
                $movement->Free_Issues = $item['Free_Issues'];
                $movement->bc          = $branch_code;
                $movement->save();

            } else {
                // UPDATE existing detail row by id
                TWithoutVatSalesDetails::where('id',         $item['id'])
                    ->where('Invoice_no', $invoiceNo)
                    ->where('BC',         $branch_code)
                    ->update([
                        'QTY'                => $item['QTY'],
                        'Unit_price'         => $item['Unit_price'],
                        'Free_Issues'        => $item['Free_Issues'],
                        'DiscountPercentage' => $item['DiscountPercentage'] ?? 0,
                        'Discount'           => $item['Discount'],
                        'Net_value'          => $item['Net_value'],
                        'OC'                 => $oc,
                    ]);

                // UPDATE stock movement quantity
                TItemMovement::where('trans_no',   $invoiceNo)
                    ->where('trans_code', 'SALES_OUT_VAT')
                    ->where('item_code',  $item['Item_code'])
                    ->where('bc',         $branch_code)
                    ->update([
                        'qun_out'     => $item['QTY'],
                        'Free_Issues' => $item['Free_Issues'],
                    ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    // ════════════════════════════════════════════════════════════
    // DELETE SINGLE ITEM ROW from a recalled invoice
    // ════════════════════════════════════════════════════════════
    public function deleteSalesInvoiceItem(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $id          = $request->input('id');

        $item = TWithoutVatSalesDetails::where('id', $id)
            ->where('BC', $branch_code)
            ->first();

        if (!$item) {
            return response()->json(['status' => 'not_found']);
        }

        // Reverse the stock movement for this specific item
        TItemMovement::where('trans_no',   $item->Invoice_no)
            ->where('trans_code', 'SALES_OUT_VAT')
            ->where('item_code',  $item->Item_code)
            ->where('bc',         $branch_code)
            ->delete();

        $item->delete();

        return response()->json(['status' => 'success']);
    }

    // ════════════════════════════════════════════════════════════
    // DELETE ENTIRE INVOICE
    // ════════════════════════════════════════════════════════════
    public function deleteSalesInvoice($invoiceNo)
    {
        $branch_code = auth()->user()->BC;

        TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)->delete();

        TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)->delete();

        TItemMovement::where('trans_no',   $invoiceNo)
            ->where('trans_code', 'SALES_OUT_VAT')
            ->where('bc',         $branch_code)->delete();

        TAccountTrans::where('trance_no',   $invoiceNo)
            ->where('trance_type', 'SALES_OUT_VAT')
            ->where('BC',          $branch_code)->delete();

        TCusSaleTrance::where('trance_no',   $invoiceNo)
            ->where('trance_type', 'SALES_OUT_VAT')
            ->where('BC',          $branch_code)->delete();

        return response()->json(['message' => 'Invoice deleted successfully.']);
    }
}