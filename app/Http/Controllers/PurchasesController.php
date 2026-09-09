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
use App\Models\TCusCheque;
use App\Models\Item;
use App\Models\Category;
use App\Models\TPurchasesSum;
use App\Models\TPurchasesDetails;
use App\Models\TItemMovement;
use App\Models\TItemSerialMovement;
use App\Models\TStockTransferDetails;
use App\Models\TStockTransferSum;
use App\Models\TSupPurchaseTrance;
use Illuminate\Support\Facades\DB;
use App\Models\BankDetails;
use App\Models\BankBranch;


class PurchasesController extends Controller
{
    public function index(){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TPurchasesSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::where('BranchCode', $branch_code)->get();
        $itemCategory = Category::all();

         $itemDetails = Item::where('BranchCode', $branch_code)->get();

         $SupplierDetails =Suppliers::all();

                 $Banks = BankDetails::all();

        $Bank_branch = BankBranch::all();


          $Customerdata = Customer::where('BC', $branch_code)->get();

           // get max Supplier number code
         $maxSupplierNo = Suppliers::orderBy('Code', 'desc')->value('Code');
         $maxSupplierNos = str_pad($maxSupplierNo, 4, '0', STR_PAD_LEFT);

        return view('purchases')

        -> with("bank", $Banks)
        -> with("bank_branch", $Bank_branch)
        ->with("itemCategory" , $itemCategory)
        ->with("SupplierData", $SupplierDetails)
        ->with("itemCode" , $itemCode)
        ->with("customerDetails", $Customerdata)
        ->with("companyData" , $companyData)
        ->with("maxSupplier", $maxSupplierNos)
        ->with("itemDetails", $itemDetails)
        ->with("maxInvoiceNo", $maxInvoice);
    }

    public function get(Request $request)
    {
        $jobNo =$request->search_string;
        $data = JobSheet::where('Job_no', $jobNo)->get();
        if($data->count() != 0){
            // dd($data );
            return response()->json([
                'status'=>'success',
                'jobNo'=> $data,
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }


public function createPurchases(Request $request)
{
    $request->validate([
        'inputs.*.customer_nic' => 'required',
        'inputs.*.invoice_no' => 'required',
        'inputs.*.invoice_date' => 'required',
        'inputs.*.item_code' => 'required',
        'inputs.*.qty' => 'required',
        'inputs.*.unit_price' => 'required',
        'inputs.*.net_value' => 'required',
        'itemSerialsField' => 'nullable|string',
    ], [
        'inputs.*.customer_nic.required' => 'The Job No field is required',
        'inputs.*.invoice_no.required' => 'The Invoice No field is required',
        'inputs.*.invoice_date.required' => 'The Invoice Date field is required',
        'inputs.*.item_code.required' => 'The Item Code field is required',
        'inputs.*.qty.required' => 'The QTY field is required',
        'inputs.*.unit_price.required' => 'The Unit Price field is required',
        'inputs.*.net_value.required' => 'The Net Value field is required',
    ]);

    DB::beginTransaction();

    try {
        $user = auth()->user();
        $branchCode = $user->BC;
        $userName = $user->username;

        // Save Purchases Summary
        $InvoiceSum = new TPurchasesSum;
        $InvoiceSum->Invoice_no = $request->invoice_no;
        $InvoiceSum->Ref_no = $request->sales_invoice_no;
        $InvoiceSum->Invoice_date = $request->invoice_date;
        $InvoiceSum->Customer_NIC = $request->customer_nic;
        $InvoiceSum->Customer_Name = $request->customer_name;
        $InvoiceSum->Customer_Phone = $request->customer_phone;
        $InvoiceSum->Gross_Amount = $request->gross_amount;
        $InvoiceSum->Net_Amount = $request->net_amount;
        $InvoiceSum->cash_payment = $request->cash_payment;
        $InvoiceSum->credit_payment = $request->credite_payment;
        $InvoiceSum->cheque_payment = $request->cheque_payment;
        $InvoiceSum->BC = $branchCode;
        $InvoiceSum->OC = $userName;
        $InvoiceSum->save();

        // Save Purchase Transaction (GRN)
        TSupPurchaseTrance::create([
            'no' => $request->invoice_no,
            'supplier' => $request->customer_nic,
            'dr_trnce_code' => 'GRN',
            'dr_trnce_no' => $request->invoice_no,
            'dr_amount' => $request->net_amount,
            'cr_trnce_code' => 'GRN',
            'cr_trnce_no' => $request->invoice_no,
            'cr_amount' => 0,
            'trance_type' => 'GRN',
            'trance_no' => $request->invoice_no,
            'dDate' => $request->invoice_date,
            'bc' => $branchCode,
            'oc' => $userName,
        ]);

            TSupPurchaseTrance::create([
            'no' => $request->invoice_no,
            'supplier' => $request->customer_nic,
            'dr_trnce_code' => 'GRN',
            'dr_trnce_no' => $request->invoice_no,
            'cr_amount' => $request->net_amount,
            'cr_trnce_code' => 'GRN',
            'cr_trnce_no' => $request->invoice_no,
            'dr_amount' => 0,
            'trance_type' => 'GRN',
            'trance_no' => $request->invoice_no,
            'dDate' => $request->invoice_date,
            'bc' => $branchCode,
            'oc' => $userName,
        ]);



        // Save Invoice Details & Item Movement
        foreach ($request->inputs as $value) {
            TPurchasesDetails::create([
                'Invoice_no' => $value['invoice_no'],
                'Ref_no' => $request->sales_invoice_no,
                'Invoice_date' => $value['invoice_date'],
                'Item_code' => $value['item_code'],
                'Item_s_code' => $value['item_s_code'] ?? null,
                'Item_description' => $value['item_description'] ?? null,
                'QTY' => $value['qty'],
                'Unit_price' => $value['unit_price'],
                'Net_value' => $value['net_value'],
                'OC' => $userName,
                'BC' => $branchCode,
            ]);

            TItemMovement::create([
                'trans_no' => $value['invoice_no'],
                'dDate' => $value['invoice_date'],
                'trans_code' => 'GRN',
                'item_code' => $value['item_code'],
                'qun_in' => $value['qty'],
                'bc' => $branchCode,
            ]);

            Item::where('item_code', $value['item_code'])->update([
                'purchasePrice' => $value['unit_price'],
            ]);
        }

        // Save Serial Numbers if provided
        $itemSerialsField = $request->input('itemSerialsField');
        if (!empty($itemSerialsField)) {
            $serialNumbersArray = json_decode($itemSerialsField, true);
            foreach ($serialNumbersArray as $itemData) {
                foreach ($itemData['serialNumbers'] as $serialNumber) {
                    TItemSerialMovement::create([
                        'trans_no' => $request->invoice_no,
                        'trans_code' => 'GRN',
                        'item_code' => $itemData['sItemCode'],
                        'item_description' => $itemData['itemName'],
                        'qun_in' => 1,
                        'item_serial_no' => $serialNumber,
                        'dDate' => $request->invoice_date,
                        'bc' => $branchCode,
                    ]);
                }
            }
        }

        // Get cheque data from request (if any)
        // $chequesData = $request->input('chequesData');
        $maxchequeNo = TCusCheque::max('trans_order_no') ?? 0;
        $maxInvoice = (int) $maxchequeNo;
        $chequesData = json_decode($request->input('chequesData'), true);

        if (!empty($chequesData)) {
            foreach ($chequesData as $cheque) {
                TCusCheque::create([
                    'trans_type' => 'SALES',
                    'cheque_status' => 'P',
                    'bank' => $cheque['bank_name'],
                    'branch' => $cheque['bank_branch'],
                    'acc_no' => $cheque['account_no'],
                    'cheques_no' => $cheque['cheque_no'],
                    'amount' => $cheque['cheque_ammount'],
                    'trans_no' => $request->invoice_no,
                    'release_date' => $cheque['cheque_date'],
                    'trans_order_no' => str_pad($maxInvoice + 1, 4, '0', STR_PAD_LEFT),
                    'oc' => $userName,
                    'bc' => $branchCode,
                ]);

                $maxInvoice++;
            }
        }

        // Prepare Data for PDF
        $companyData = Company::latest()->paginate(1);
        $T_detailsdata = TPurchasesDetails::where('Invoice_no', $request->invoice_no)
            ->where('BC', $branchCode)
            ->get();

        $T_sumdata = TPurchasesSum::where('Invoice_no', $request->invoice_no)
            ->where('BC', $branchCode)
            ->get();

        $supplierData = Suppliers::where('Code', $request->customer_nic)->get();

        $pdf = PDF::loadView('purchaseInvoicePrint', [
            'pawnSumData' => $T_sumdata,
            'supplierData' => $supplierData,
            'pawnDetailsData' => $T_detailsdata,
            'companyData' => $companyData
        ]);

        $pdfPath = storage_path('../public/assets/pdf/Purchase_Invoice.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('public/assets/pdf/Purchase_Invoice.pdf');

        DB::commit();

        return back()
            ->with('done', 'The Invoice has been added')
            ->with('pdfLink', $pdfUrl);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Failed to save Purchase Invoice. ' . $e->getMessage()]);
    }
}

public function isSerial(Request $request)
{
    $description = $request->description;

    $manual = Item::where('Item_description', $description)
        ->where('Serialnumber', '1')
        ->exists();

    $auto = Item::where('Item_description', $description)
        ->where('AutoSerialnumber', '1')
        ->exists();

    return response()->json([
        'manual_serial' => $manual,
        'auto_serial' => $auto
    ]);
}



    // get and show items according to category
    public function setItemsCode(Request $request){
        $category = $request->category;
        $data = Item::where('category',$category)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

     // get and show items according to category
     public function setItemDescription(Request $request){
        $Item_code = $request->Item_code;
        $data = Item::where('Item_code',$Item_code)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


     // find invoice customer data
     public function findInvoiceCustomerData(Request $request){
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
    // find invoice details
    public function findInvoice(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TPurchasesDetails::where('Invoice_no',$receiptNo)
                    ->where('BC',$branch_code)
                    ->get();
        if($data->count() != null){
            return view('purchase_invoice_find_recall_details')
            // ->with("itemsDetails" , $itemsData)
            ->with('invoiceData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

// find invoice customer data
public function findSalesInvoiceCustomerData(Request $request){
    $branch_code = auth()->user()->BC;
    $user_name = auth()->user()->username;
    $receiptNo = $request->search_receipt_no;
    $data = TStockTransferSum::where('Invoice_no',$receiptNo)
            // ->where('BC',$branch_code)
            ->where('To_Branch',$branch_code)
            ->where('Is_Purchased',0)
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
    // find invoice details
    public function findSalesInvoice(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TStockTransferDetails::where('Invoice_no',$receiptNo)
                    // ->where('BC',$branch_code)
                    ->where('To_Branch',$branch_code)
                    ->where('Is_Purchased',0)
                    ->get();
        if($data->count() != null){
            return view('purchase_invoice_find_recall_details')
            // ->with("itemsDetails" , $itemsData)
            ->with('invoiceData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }



    public function destroy(Request $request)
    {
        Invoice::find($request->invoice_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }



}