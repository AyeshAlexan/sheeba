<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TInvoiceSum;
use App\Models\TInvoiceDeils;
use App\Models\TItemMovement;
use App\Models\TAccountTrans;
use App\Models\TCusSaleTrance;

/**
 * VAT-table-correct counterpart to RecallEditSalesInvoiceController, which
 * only ever queries the non-VAT tables (TwithoutVatSalesSum /
 * TWithoutVatSalesDetails). salesInvoice.blade.php (the VAT invoice screen)
 * was wired to that controller by mistake — recalling an invoice there
 * searched the wrong tables entirely. This controller does the same job
 * against TInvoiceSum / TInvoiceDeils, the tables InvoiceController::
 * createInvoice()/print() actually use.
 */
class RecallEditInvoiceController extends Controller
{
    public function findInvoiceDetails(Request $request)
    {
        $validated = $request->validate([
            'search_receipt_no' => 'required|string|max:255',
        ]);

        $branchCode = Auth::user()->BC;
        $receiptNo = $validated['search_receipt_no'];

        $invoiceData = TInvoiceDeils::where('Invoice_no', $receiptNo)
            ->where('BC', $branchCode)
            ->get();

        if ($invoiceData->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => $invoiceData,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'message' => 'Invoice not found.',
        ]);
    }

    public function findInvoiceDataSum(Request $request)
    {
        $branchCode = Auth::user()->BC;
        $receiptNo = $request->search_receipt_no;

        $data = TInvoiceSum::where('Invoice_no', $receiptNo)
            ->where('BC', $branchCode)
            ->get();

        if ($data->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }

    public function updateInvoiceData(Request $request)
    {
        $request->validate([
            'invoice.invoice_no' => 'required|string',
            'invoice.invoice_date' => 'required|date',
            'invoice.customer_nic' => 'required|string',
            'invoice.total_amount' => 'required|numeric',
            'invoice.paid_discount' => 'required|numeric',
            'invoice.paid_amount' => 'required|numeric',
            'invoice.cash_payment' => 'nullable|numeric|min:0',
            'invoice.credit_payment' => 'nullable|numeric|min:0',
            'invoice.cheque_payment' => 'nullable|numeric|min:0',
            'invoice.items' => 'required|array|min:1',
            'invoice.items.*.Item_code' => 'required|string',
            'invoice.items.*.Item_description' => 'required|string',
            'invoice.items.*.Unit_price' => 'required|numeric',
            'invoice.items.*.QTY' => 'required|numeric',
            'invoice.items.*.Net_value' => 'numeric',
            'invoice.items.*.Discount' => 'nullable|numeric',
        ]);

        $data = $request->input('invoice');
        $branchCode = auth()->user()->BC;
        $userName = auth()->user()->username;

        $cashPayment = (float) ($data['cash_payment'] ?? 0);
        $creditPayment = (float) ($data['credit_payment'] ?? 0);
        $chequePayment = (float) ($data['cheque_payment'] ?? 0);

        DB::beginTransaction();

        try {
            $invoice = TInvoiceSum::where('Invoice_no', $data['invoice_no'])
                ->where('BC', $branchCode)
                ->first();

            if (!$invoice) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Invoice not found.']);
            }

            $invoice->update([
                'Invoice_date' => $data['invoice_date'],
                'Customer_NIC' => $data['customer_nic'],
                'Gross_Amount' => $data['total_amount'],
                'Discount' => $data['paid_discount'],
                'Net_Amount' => $data['paid_amount'],
                'Cash_Pay' => $cashPayment,
                'Credite' => $creditPayment,
                'Cheque' => $chequePayment,
            ]);

            // Same ledger convention as InvoiceController::createInvoice —
            // re-derive both account and customer ledger rows from scratch
            // rather than trying to diff against the old values.
            TAccountTrans::where('trance_no', $data['invoice_no'])
                ->where('trance_type', 'SALES')
                ->delete();
            TCusSaleTrance::where('no', $data['invoice_no'])
                ->where('trance_type', 'SALES')
                ->delete();

            TAccountTrans::create([
                'trance_type' => 'SALES',
                'Ddate' => $data['invoice_date'],
                'AccCode' => $data['customer_nic'],
                'Description' => $data['items'][0]['Item_description'] ?? '',
                'dr_amount' => 0,
                'cr_amount' => $cashPayment,
                'trance_no' => $data['invoice_no'],
                'no' => $data['invoice_no'],
                'BC' => $branchCode,
                'OC' => $userName,
            ]);

            $receivedAmount = $cashPayment > 0 ? $cashPayment : ($creditPayment > 0 ? $creditPayment : $chequePayment);

            TCusSaleTrance::create([
                'no' => $data['invoice_no'],
                'customer' => $data['customer_nic'],
                'dr_trnce_code' => 'SALES',
                'dr_trnce_no' => $data['invoice_no'],
                'cr_trnce_code' => 'SALES',
                'cr_trnce_no' => $data['invoice_no'],
                'dr_amount' => 0,
                'cr_amount' => $receivedAmount,
                'trance_type' => 'SALES',
                'trance_no' => $data['invoice_no'],
                'Display_Ref' => $data['invoice_no'],
                'dDate' => $data['invoice_date'],
                'BC' => $branchCode,
                'OC' => $userName,
            ]);

            if ($cashPayment > 0 || $chequePayment > 0) {
                TCusSaleTrance::create([
                    'no' => $data['invoice_no'],
                    'customer' => $data['customer_nic'],
                    'dr_trnce_code' => 'SALES',
                    'dr_trnce_no' => $data['invoice_no'],
                    'cr_trnce_code' => 'SALES',
                    'cr_trnce_no' => $data['invoice_no'],
                    'dr_amount' => $cashPayment > 0 ? $cashPayment : $chequePayment,
                    'cr_amount' => 0,
                    'trance_type' => 'SALES',
                    'trance_no' => $data['invoice_no'],
                    'Display_Ref' => $data['invoice_no'],
                    'dDate' => $data['invoice_date'],
                    'BC' => $branchCode,
                    'OC' => $userName,
                ]);
            }

            TInvoiceDeils::where('Invoice_no', $data['invoice_no'])
                ->where('BC', $branchCode)
                ->delete();

            TItemMovement::where('trans_no', $data['invoice_no'])
                ->where('trans_code', 'SALES')
                ->where('bc', $branchCode)
                ->delete();

            foreach ($data['items'] as $item) {
                TInvoiceDeils::create([
                    'Invoice_no' => $data['invoice_no'],
                    'Invoice_date' => $data['invoice_date'],
                    'Item_code' => $item['Item_code'],
                    'Item_description' => $item['Item_description'],
                    'Unit_price' => $item['Unit_price'],
                    'QTY' => $item['QTY'],
                    'Discount' => $item['Discount'] ?? 0,
                    'Net_value' => $item['Net_value'],
                    'OC' => $userName,
                    'BC' => $branchCode,
                ]);

                TItemMovement::create([
                    'trans_no' => $data['invoice_no'],
                    'dDate' => $data['invoice_date'],
                    'trans_code' => 'SALES',
                    'item_code' => $item['Item_code'],
                    'qun_out' => $item['QTY'],
                    'bc' => $branchCode,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Invoice updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating VAT sales invoice: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the invoice.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleteInvoiceData($invoiceNo)
    {
        DB::beginTransaction();

        try {
            $branchCode = auth()->user()->BC;

            TInvoiceDeils::where('Invoice_no', $invoiceNo)
                ->where('BC', $branchCode)
                ->delete();

            TItemMovement::where('trans_no', $invoiceNo)
                ->where('trans_code', 'SALES')
                ->where('bc', $branchCode)
                ->delete();

            TInvoiceSum::where('Invoice_no', $invoiceNo)
                ->where('BC', $branchCode)
                ->delete();

            TAccountTrans::where('trance_no', $invoiceNo)
                ->where('trance_type', 'SALES')
                ->delete();

            TCusSaleTrance::where('no', $invoiceNo)
                ->where('trance_type', 'SALES')
                ->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Delete failed.', 'error' => $e->getMessage()]);
        }
    }
}
