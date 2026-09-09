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
use App\Models\TStockTransferDetails;
use App\Models\TStockTransferSum;



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
        $itemCode = Item::all();
        $itemCategory = Category::all();
        $itemDetails =Item::latest()->paginate(5);

           // get max Supplier number code
         $maxSupplierNo = Suppliers::orderBy('Code', 'desc')->value('Code');
         $maxSupplierNos = str_pad($maxSupplierNo, 4, '0', STR_PAD_LEFT);

        return view('purchases')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
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


   public function createPurchases(Request $request){
         $request->validate([
            'inputs.*.customer_nic' => ' required',
            'inputs.*.invoice_no' => ' required',
            'inputs.*.invoice_date' => ' required',
            // 'inputs.*.item_category' => ' required',
            'inputs.*.item_code' => ' required',
            // 'inputs.*.item_description' => ' required',
            'inputs.*.qty' => ' required',
            'inputs.*.unit_price' => ' required',
            'inputs.*.net_value' => ' required'
        ],[
            'inputs.*.customer_nic' => ' The Job No field is required',
            'inputs.*.invoice_no' => ' The Invoice No field is required',
            'inputs.*.invoice_date' => ' The Invoice Date field is required',
            // 'inputs.*.item_category' => ' The Item Category field is required',
            'inputs.*.item_code' => ' The Item Code field is required',
            'inputs.*.item_description' => ' The Item Description field is required',
            'inputs.*.qty' => ' The QTY field is required',
            'inputs.*.unit_price' => ' The Unit Price field is required',
            'inputs.*.net_value' => ' The Net Value field is required'
        ]);

        $InvoiceSum = new TPurchasesSum;
        $InvoiceSum->Invoice_no = $request->invoice_no;
        $InvoiceSum->Ref_no = $request->sales_invoice_no;
        $InvoiceSum->Invoice_date = $request->invoice_date;
        $InvoiceSum->Customer_NIC = $request->customer_nic;
        $InvoiceSum->Customer_Name = $request->customer_name;
        $InvoiceSum->Customer_Phone = $request->customer_phone;
        $InvoiceSum->Gross_Amount = $request->gross_amount;
        $InvoiceSum->Discount =  $request->discount;
        $InvoiceSum->Net_Amount =  $request->net_amount;
        $InvoiceSum->cash_payment =  $request->cash_payment;
        $InvoiceSum->credit_payment =  $request->credit_payment;
        $InvoiceSum->cheque_payment =  $request->cheque_payment;
        $InvoiceSum->BC = auth()->user()->BC;
        $InvoiceSum->OC = auth()->user()->username;
        $InvoiceSum->save();

        foreach ($request -> inputs as $key=>$value){
        $InvoiceDetails = new TPurchasesDetails;
        $ItemMovementDetails = new TItemMovement;

        $InvoiceDetails->Invoice_no=$value['invoice_no'];
        $InvoiceDetails->Ref_no= $request->sales_invoice_no;
        $InvoiceDetails->Invoice_date=$value['invoice_date'];
        $InvoiceDetails->Item_code=$value['item_code'];
        $InvoiceDetails->Item_description=$value['item_description'];
        $InvoiceDetails->QTY=$value['qty'];
        $InvoiceDetails->Unit_price=$value['unit_price'];
        $InvoiceDetails->Discount=$value['discount_val'];
        $InvoiceDetails->Net_value=$value['net_value'];
        $InvoiceDetails->OC= auth()->user()->username;
        $InvoiceDetails->BC= auth()->user()->BC;
        $InvoiceDetails->save();

        $ItemMovementDetails->trans_no=$value['invoice_no'];
        $ItemMovementDetails->dDate=$value['invoice_date'];
        $ItemMovementDetails->trans_code="GRN";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_in=$value['qty'];
        $ItemMovementDetails->bc = auth()->user()->BC;
        $ItemMovementDetails->save();
        }

        // Step 1: Get the value of the itemSerialsField from the request
        $itemSerialsField = $request->input('itemSerials');

        if($itemSerialsField != null){
            // Step 2: Decode the JSON string back into an array
            $serialNumbersArray = json_decode($itemSerialsField, true);

            foreach ($serialNumbersArray as $itemData) {
                $sItemCode = $itemData['sItemCode'];
                $itemQuantity = $itemData['itemQuantity'];
                $itemName = $itemData['itemName'];
                $transNo = $request->invoice_no;
                $transCode = "GRN";
                $dDate = $request->invoice_date;
                $bc = auth()->user()->BC;

                // Loop through each serial number and save a new row for each
                foreach ($itemData['serialNumbers'] as $serialNumber) {
                    $itemSerialTransaction = new TItemSerialMovement;

                    // Assign values to the model attributes
                    $itemSerialTransaction->trans_no = $transNo;
                    $itemSerialTransaction->trans_code = $transCode;
                    $itemSerialTransaction->item_code = $sItemCode;
                    $itemSerialTransaction->item_description = $itemName;
                    $itemSerialTransaction->qun_in = 1;
                    $itemSerialTransaction->item_serial_no = $serialNumber;
                    $itemSerialTransaction->dDate = $dDate;
                    $itemSerialTransaction->bc = $bc;

                    // Save the record to the table
                    $itemSerialTransaction->save();
                }
            }

        }


        $companyData = Company::latest()->paginate(1);
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $invoiceInput_no = $request->invoice_no;
        $T_detailsdata = TPurchasesDetails::where('Invoice_no', $invoiceInput_no)
                        ->where('BC', $branch_code)
                        ->get();

        $T_sumdata = TPurchasesSum::where('Invoice_no', $invoiceInput_no)
                    ->where('BC', $branch_code)
                    ->get();

        $supplier_code = $request->customer_nic;
        $supplierData = Suppliers::where('Code', $supplier_code)
                                    ->get();

        // Generate the PDF content using a view
        $pdf = PDF::loadView('purchaseInvoicePrint', [
            'pawnSumData' => $T_sumdata ,
            'supplierData' => $supplierData,
            'pawnDetailsData' => $T_detailsdata,
            'companyData' => $companyData
        ]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Purchase_Invoice.pdf');
        // $pdfPath = storage_path('../pdf/Purchase_Invoice.pdf');
        $pdf->save($pdfPath);

        // $pdfUrl = attach('pdf/Purchase_Invoice.pdf');
        $pdfUrl = asset('public/assets/pdf/Purchase_Invoice.pdf');

        return back()
        ->with('done','The Invoice has been added')
        ->with("pdfLink", $pdfUrl);
    }



    // save recall purchase
    public function createRecallPurchases(Request $request){
            $request->validate([
                'invoice_no'=>'required',
                'invoice_date'=>'required',
                'customer_nic'=>'required','max:15',
                'customer_name'=>'required',
                'gross_amount'=>'required',
                'net_amount'=>'required',
            ]);

            $InvoiceSum = new TPurchasesSum;
            $InvoiceSum->Invoice_no = $request->invoice_no;
            $InvoiceSum->Invoice_date = $request->invoice_date;
            $InvoiceSum->Customer_NIC = $request->customer_nic;
            $InvoiceSum->Customer_Name = $request->customer_name;
            $InvoiceSum->Customer_Phone = $request->customer_phone;
            $InvoiceSum->Gross_Amount = $request->gross_amount;
            $InvoiceSum->Discount =  $request->discount;
            $InvoiceSum->Net_Amount =  $request->net_amount;
            $InvoiceSum->BC = auth()->user()->BC;
            $InvoiceSum->OC = auth()->user()->username;
            $InvoiceSum->save();


            // Retrieve the JSON-encoded array from the request
            $purchaseInputs = json_decode($request->input('inputs'), true);


        // Loop through the decoded array and save each row to the database
        foreach ($purchaseInputs as $input) {
            $invoiceDetails = new TPurchasesDetails;
            $itemMovementDetails = new TItemMovement;

            $invoiceDetails->Invoice_no = $input['invoice_no'];
            $invoiceDetails->Invoice_date = $input['invoice_date'];
            // $invoiceDetails->Item_category = $input['item_category'];
            $invoiceDetails->Item_code = $input['item_code'];
            $invoiceDetails->Item_description = $input['item_description'];
            $invoiceDetails->QTY = $input['qty'];
            $invoiceDetails->Unit_price = $input['unit_price'];
            $invoiceDetails->Discount = $input['discount_val'];
            $invoiceDetails->Net_value = $input['net_value'];
            $invoiceDetails->OC = auth()->user()->username;
            $invoiceDetails->BC = auth()->user()->BC;
            $invoiceDetails->save();

            $itemMovementDetails->trans_no = $input['invoice_no'];
            $itemMovementDetails->dDate = $input['invoice_date'];
            $itemMovementDetails->trans_code = "GRN";
            $itemMovementDetails->item_code = $input['item_code'];
            $itemMovementDetails->qun_in = $input['qty'];
            $itemMovementDetails->bc = auth()->user()->BC;
            $itemMovementDetails->save();
        }
            $Invoice_no = $request->invoice_no;
            TStockTransferSum::where('Invoice_no', $Invoice_no)->update([
                'Is_Purchased'=>"1",
            ]);
            TStockTransferDetails::where('Invoice_no', $Invoice_no)->update([
                'Is_Purchased'=>"1",
            ]);

            return response()->json([
                'status'=>'success',
            ]);
    }


    public function isSerial(Request $request){
        $description = $request->description;
        $data = Item::where('Item_description',$description)
                ->where('Serialnumber','1')
                ->get();

        if($data->count() != 0){
            return response()->json([
                'status'=>'success',
            ]);

        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
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
