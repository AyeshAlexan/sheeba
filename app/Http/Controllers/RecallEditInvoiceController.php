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
use App\Models\TSupPurchaseTrance;
use Illuminate\Support\Facades\DB;

class RecallEditInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
    
        // get max invoice number max
        $maxInvoiceNo = TPurchasesSum::orderBy('Invoice_no', 'desc')
                        ->where('BC', $branch_code)
                        ->value('Invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);
    
        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();
        $itemDetails = Item::latest()->paginate(5);
    
        $puruchaseCode = TPurchasesSum::all();

        foreach ($puruchaseCode as $invoice) {
            echo $invoice->Invoice_no;  // Check if it has Invoice_no
        }
    
        // get max Supplier number code
        $maxSupplierNo = Suppliers::orderBy('Code', 'desc')->value('Code');
        $maxSupplierNos = str_pad($maxSupplierNo, 4, '0', STR_PAD_LEFT);
    
        return view('puruchaseEdit')
            ->with("purchase", $puruchaseCode) // Corrected this line
            ->with("itemCategory", $itemCategory)
            ->with("itemCode", $itemCode)
            ->with("companyData", $companyData)
            ->with("maxSupplier", $maxSupplierNos)
            ->with("itemDetails", $itemDetails)
            ->with("maxInvoiceNo", $maxInvoice);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findSalesInvoiceDataSum(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TPurchasesSum::where('Invoice_no',$receiptNo)
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

    public function FindSalesInvoiceDetails(Request $request)
        {
            // Validate the request
            $validated = $request->validate([
                'search_receipt_no' => 'required|string|max:255',
            ]);

            $branchCode = Auth::user()->BC; // Branch Code
            $receiptNo = $validated['search_receipt_no']; // Sanitize user input

            // Fetch data from TPurchasesDetails table
            $invoiceData = TPurchasesDetails::where('Invoice_no', $receiptNo)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function updatePurchaseInvoice(Request $request)
{
    // Validate the request
    $request->validate([
        'invoice.invoice_no' => 'required|string',
        'invoice.invoice_date' => 'required|date',
        'invoice.customer_nic' => 'required|string',
        'invoice.total_amount' => 'required|numeric',
        'invoice.paid_amount' => 'required|numeric',
        'invoice.cash_payment' => 'nullable|numeric',
        'invoice.credit_payment' => 'nullable|numeric',
        'invoice.cheque_payment' => 'nullable|numeric',
        'invoice.items' => 'required|array|min:1',
        'invoice.items.*.Item_code' => 'required|string',
        'invoice.items.*.Item_description' => 'required|string',
        'invoice.items.*.Unit_price' => 'required|numeric',
        'invoice.items.*.QTY' => 'required|numeric',
        'invoice.items.*.Net_value' => 'numeric',
        'invoice.items.*.DiscountPercentage' =>'nullable|numeric',
    ]);

    $data = $request->input('invoice');

    DB::beginTransaction();

    try {
        // Fetch the existing invoice
        $invoice = TPurchasesSum::where('Invoice_no', $data['invoice_no'])
            ->where('BC', auth()->user()->BC)
            ->first();

        if (!$invoice) {
            return response()->json(['status' => 'error', 'message' => 'Invoice not found.']);
        }

        // Update the invoice header
        $invoice->update([
            'Invoice_date' => $data['invoice_date'],
            'Customer_NIC' => $data['customer_nic'],
            'Gross_Amount' => $data['total_amount'],
            'Discount' => $data['paid_discount'],
            'Net_Amount' => $data['paid_amount'],
            'cash_payment' => $data['cash_payment'] ?? 0,
            'credit_payment' => $data['credit_payment'] ?? 0,
            'cheque_payment' => $data['cheque_payment'] ?? 0,
        ]);

        // Delete existing items
        TPurchasesDetails::where('Invoice_no', $data['invoice_no'])
            ->where('BC', auth()->user()->BC)
            ->delete();

        TItemMovement::where('trans_no', $data['invoice_no'])
            ->where('trans_code', 'GRN')
            ->where('BC', auth()->user()->BC)
            ->delete();

        // Insert new items and movement records
        foreach ($data['items'] as $item) {
            TPurchasesDetails::create([
                'Invoice_no' => $data['invoice_no'],
                'Item_code' => $item['Item_code'],
                'Item_description' => $item['Item_description'],
                'Unit_price' => $item['Unit_price'],
                'QTY' => $item['QTY'],
                'Discount' => $item['Discount'],
                'DiscountPercentage' => $item['DiscountPercentage'],
                'Net_value' => $item['Net_value'],
                'BC' => auth()->user()->BC,
                'OC' => auth()->user()->username,
            ]);

            TItemMovement::create([
                'trans_no' => $data['invoice_no'],
                'dDate' => $data['invoice_date'],
                'trans_code' => 'GRN',
                'item_code' => $item['Item_code'],
                'qun_in' => $item['QTY'],
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function deletePurchaseInvoice($invoiceNo)
{
    DB::beginTransaction();
    try {
        TPurchasesDetails::where('Invoice_no', $invoiceNo)
            ->where('BC', auth()->user()->BC)
            ->delete();

        TItemMovement::where('trans_no', $invoiceNo)
            ->where('trans_code', 'GRN')
            ->where('BC', auth()->user()->BC)
            ->delete();

        TPurchasesSum::where('Invoice_no', $invoiceNo)
            ->where('BC', auth()->user()->BC)
            ->delete();

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 'error', 'message' => 'Delete failed.', 'error' => $e->getMessage()]);
    }
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