<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Suppliers;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Category;
use App\Models\TPurchasesSum;
use App\Models\TPurchasesDetails;
use App\Models\TItemMovement;
use App\Models\TItemSerialMovement;
use App\Models\TInvoiceDeils;
use App\Models\TInvoiceSum;
use App\Models\TwithoutVatSalesSum;
use App\Models\TWithoutVatSalesDetails;
use App\Models\TSupPurchaseTrance;
use App\Models\TAccountTran;
use App\Models\TCusSaleTrance;

use Illuminate\Support\Facades\DB;

class RecallEditSalesInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function FindInvoiceDetails(Request $request)
          {
            // Validate the request
            $validated = $request->validate([
                'search_receipt_no' => 'required|string|max:255',
            ]);

            $branchCode = Auth::user()->BC; // Branch Code
            $receiptNo = $validated['search_receipt_no']; // Sanitize user input

            // Fetch data from TPurchasesDetails table
            $invoiceData = TWithoutVatSalesDetails::where('Invoice_no', $receiptNo)
                ->where('BC', $branchCode)
                ->get();

            // Return response based on data availability
            if ($invoiceData->isNotEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $invoiceData,
                ]);
            } else {
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'Invoice not found.',
                ]);
            }
        }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function RecallSalesInvoiceDataSum(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TwithoutVatSalesSum::where('Invoice_no',$receiptNo)
                ->where('BC',$branch_code)
                ->get();
        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdateSaleData(Request $request)
    {
        // Validate the request
        $request->validate([
            'invoice.invoice_no' => 'required|string',
            'invoice.invoice_date' => 'required|date',
            'invoice.customer_nic' => 'required|string',
            'invoice.customer_name' => 'required|string',
            'invoice.total_amount' => 'required|numeric',
            'invoice.paid_discount' => 'required|numeric',
            'invoice.paid_amount' => 'required|numeric',
            'invoice.cash_payment' => 'nullable|numeric|min:0',
            'invoice.half_payment' => 'nullable|numeric|min:0',
            'invoice.credite_payment' => 'nullable|numeric|min:0',
            'invoice.cheque_payment' => 'nullable|numeric|min:0',
            'invoice.items' => 'required|array|min:1',
            'invoice.items.*.Item_code' => 'required|string',
            'invoice.items.*.Item_description' => 'required|string',
            'invoice.items.*.Unit_price' => 'required|numeric',
            'invoice.items.*.QTY' => 'required|numeric',
            'invoice.items.*.Net_value' => 'numeric',
            'invoice.items.*.DiscountPercentage' =>'nullable|numeric',
            'invoice.items.*.Discount' => 'nullable|numeric',

        ]);

        $data = $request->input('invoice');
        $netAmount = (float) $data['paid_amount'];
        $cashPayment = (float) ($data['cash_payment'] ?? 0);
        $halfPayment = (float) ($data['half_payment'] ?? 0);
        $chequePayment = (float) ($data['cheque_payment'] ?? 0);

        if ($cashPayment > 0 && $halfPayment > 0) {
            return response()->json(['status' => 'error', 'message' => 'Use either Cash Pay or Half Payment, not both.']);
        }

        $creditPayment = round($netAmount - $cashPayment - $halfPayment - $chequePayment, 2);
        if ($creditPayment < 0) {
            return response()->json(['status' => 'error', 'message' => 'Payment amounts cannot be greater than the net amount.']);
        }

        DB::beginTransaction();

        try {
            // Fetch the existing invoice
            $invoice = TwithoutVatSalesSum::where('Invoice_no', $data['invoice_no'])
                ->where('BC', auth()->user()->BC)
                ->first();

            if (!$invoice) {
                return response()->json(['status' => 'error', 'message' => 'Invoice not found.']);
            }

            // Update the invoice header
            $invoice->update([
                'Invoice_date' => $data['invoice_date'],
                'Customer_NIC' => $data['customer_nic'],
                'Customer_Name' => $data['customer_name'],
                'Gross_Amount' => $data['total_amount'],
                'Route' => $data['Route'],
                'Salesmen' => $data['Salesmen'],
                'Discount' => $data['paid_discount'],
                'Net_Amount' => $data['paid_amount'],
                'Cash_Pay' => $cashPayment,
                'Half_Payment' => $halfPayment,
                'Credite' => $creditPayment,
                'Cheque' => $chequePayment,
            ]);

            DB::table('t_account_trans')
                ->where('trance_no', $data['invoice_no'])
                ->where('trance_type', 'SALES_OUT_VAT')
                ->delete();
            TCusSaleTrance::where('no', $data['invoice_no'])
                ->where('trance_type', 'SALES_OUT_VAT')
                ->delete();

            $insertAccount = function (string $accountCode, float $debit, float $credit, string $description) use ($data) {
                DB::table('t_account_trans')->insert([
                    'trance_type' => 'SALES_OUT_VAT',
                    'Ddate' => $data['invoice_date'],
                    'AccCode' => $accountCode,
                    'Description' => $description,
                    'dr_amount' => $debit,
                    'cr_amount' => $credit,
                    'trance_no' => $data['invoice_no'],
                    'no' => $data['invoice_no'],
                    'BC' => auth()->user()->BC,
                    'OC' => auth()->user()->username,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            };

            $insertCustomer = function (float $debit, float $credit) use ($data) {
                TCusSaleTrance::create([
                    'no' => $data['invoice_no'],
                    'customer' => $data['customer_nic'],
                    'dr_trnce_code' => 'SALES_OUT_VAT',
                    'dr_trnce_no' => $data['invoice_no'],
                    'cr_trnce_code' => 'SALES_OUT_VAT',
                    'cr_trnce_no' => $data['invoice_no'],
                    'dr_amount' => $debit,
                    'cr_amount' => $credit,
                    'trance_type' => 'SALES_OUT_VAT',
                    'trance_no' => $data['invoice_no'],
                    'Display_Ref' => $data['invoice_no'],
                    'dDate' => $data['invoice_date'],
                    'BC' => auth()->user()->BC,
                    'OC' => auth()->user()->username,
                ]);
            };

            if ($chequePayment > 0) {
                $insertAccount($data['customer_nic'], 0, $chequePayment, 'Cheque Payment Adjustment');
                $insertCustomer(0, $chequePayment);
            }
            if ($creditPayment > 0) {
                $insertAccount($data['customer_nic'], 0, $creditPayment, 'Credit Sale Entry');
                $insertCustomer(0, $creditPayment);
            }

            $cashReceived = $cashPayment + $halfPayment;
            if ($cashReceived > 0) {
                $insertAccount('201-001', $cashReceived, 0, 'Cash Payment Received');
                $insertCustomer($cashReceived, 0);
                $insertAccount($data['customer_nic'], 0, $cashReceived, 'Cash Payment Adjustment');
                $insertCustomer(0, $cashReceived);
            }

            // Delete existing items
            TWithoutVatSalesDetails::where('Invoice_no', $data['invoice_no'])
                ->where('BC', auth()->user()->BC)
                ->delete();

            TItemMovement::where('trans_no', $data['invoice_no'])
                ->where('trans_code', 'SALES_OUT_VAT')
                ->where('BC', auth()->user()->BC)
                ->delete();

            // Insert new items and movement records
            foreach ($data['items'] as $item) {
                TWithoutVatSalesDetails::create([
                    'Invoice_no' => $data['invoice_no'],
                    'Item_code' => $item['Item_code'],
                    'Item_description' => $item['Item_description'],
                    'Unit_price' => $item['Unit_price'],
                    'QTY' => $item['QTY'],
                    'Discount' => $item['Discount'],
                    'DiscountPercentage' => $item['DiscountPercentage'],
                    'Net_value' => $item['Net_value'],
                    'Free_Issues' => $item['Free_Issues'],
                    'BC' => auth()->user()->BC,
                    'OC' => auth()->user()->username,
                ]);

                TItemMovement::create([
                    'trans_no' => $data['invoice_no'],
                    'dDate' => $data['invoice_date'],
                    'trans_code' => 'SALES_OUT_VAT',
                    'item_code' => $item['Item_code'],
                    'qun_out' => $item['QTY'],
                    'Free_Issues' => $item['Free_Issues'],
                    'bc' => auth()->user()->BC,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Invoice updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating purchase invoice: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the invoice.',
                'error' => $e->getMessage() // Show error for debugging
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function DeleteSalesInvoice($invoiceNo)
        {
            DB::beginTransaction();
            try {
                $branchCode = auth()->user()->BC;

                // Delete related sales details
                TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
                    ->where('BC', $branchCode)
                    ->delete();

                // Delete item movements
                TItemMovement::where('trans_no', $invoiceNo)
                    ->where('trans_code', 'SALES_OUT_VAT')
                    ->where('BC', $branchCode)
                    ->delete();

                // Delete sales summary
                TwithoutVatSalesSum::where('Invoice_no', $invoiceNo)
                    ->where('BC', $branchCode)
                    ->delete();

                // Delete account transactions
                TAccountTran::where('trance_no', $invoiceNo)
                    ->where('trance_type', 'SALES_OUT_VAT')
                    ->delete();

                // Delete customer sale transactions
                TCusSaleTrance::where('no', $invoiceNo)
                    ->where('trance_type', 'SALES_OUT_VAT')
                    ->delete();

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Delete failed.', 'error' => $e->getMessage()]);
            }
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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