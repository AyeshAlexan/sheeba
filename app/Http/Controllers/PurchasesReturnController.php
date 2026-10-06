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
use App\Models\TPurchasesReturnSum;
use App\Models\TPurchasesReturnDetails;
use App\Models\TItemMovement;
use App\Models\TStockTransferDetails;
use App\Models\TStockTransferSum;



class PurchasesReturnController extends Controller
{
    public function index(){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TPurchasesReturnSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();
        $itemDetails = Item::latest()->get();

         // get max Customer number code
         $maxSupplierNo = Suppliers::orderBy('Code', 'desc')->value('Code');
         $maxSupplierNos = str_pad($maxSupplierNo, 4, '0', STR_PAD_LEFT);

        return view('purchases_Return')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxSupplier", $maxSupplierNos)
        ->with("itemDetails", $itemDetails)
        ->with("SupplierData", Suppliers::all())
        ->with("maxInvoiceNo", $maxInvoice);
    }

    public function createPurchasesReturn(Request $request){
        $request->validate([
           'inputs.*.customer_nic' => ' required',
           'inputs.*.invoice_no' => ' required',
           'inputs.*.invoice_date' => ' required',
           'inputs.*.item_code' => ' required',
           'inputs.*.qty' => ' required',
           'inputs.*.unit_price' => ' required',
           'inputs.*.net_value' => ' required'
       ],[
           'inputs.*.customer_nic' => ' The Job No field is required',
           'inputs.*.invoice_no' => ' The Invoice No field is required',
           'inputs.*.invoice_date' => ' The Invoice Date field is required',
           'inputs.*.item_code' => ' The Item Code field is required',
           'inputs.*.item_description' => ' The Item Description field is required',
           'inputs.*.qty' => ' The QTY field is required',
           'inputs.*.unit_price' => ' The Unit Price field is required',
           'inputs.*.net_value' => ' The Net Value field is required'
       ]);

       $InvoiceSum = new TPurchasesReturnSum;
       $InvoiceSum->Invoice_no = $request->invoice_no;
       $InvoiceSum->Invoice_date = $request->invoice_date;
       $InvoiceSum->Supplier_Code = $request->supplier_code;
       $InvoiceSum->Supplier_Name = $request->supplier_name;
       $InvoiceSum->Supplier_Phone = $request->supplier_phone;
       $InvoiceSum->Gross_Amount = $request->gross_amount;
       $InvoiceSum->Discount =  $request->discount;
       $InvoiceSum->Net_Amount =  $request->net_amount;
       $InvoiceSum->BC = auth()->user()->BC;
       $InvoiceSum->OC = auth()->user()->username;
       $InvoiceSum->save();

       foreach ($request -> inputs as $key=>$value){
       $InvoiceDetails = new TPurchasesReturnDetails;
       $ItemMovementDetails = new TItemMovement;

       $InvoiceDetails->Invoice_no=$value['invoice_no'];
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
       $ItemMovementDetails->trans_code="PRN";
       $ItemMovementDetails->item_code=$value['item_code'];
       $ItemMovementDetails->qun_out=$value['qty'];
       $ItemMovementDetails->bc = auth()->user()->BC;
       $ItemMovementDetails->save();
       }

       $companyData = Company::latest()->paginate(1);
       $branch_code = auth()->user()->BC;
       $user_name = auth()->user()->username;

       $invoiceInput_no = $request->invoice_no;
       $T_detailsdata = TPurchasesReturnDetails::where('Invoice_no', $invoiceInput_no)
                       ->where('BC', $branch_code)
                       ->get();

       $T_sumdata = TPurchasesReturnSum::where('Invoice_no', $invoiceInput_no)
                   ->where('BC', $branch_code)
                   ->get();

       // Generate the PDF content using a view
       $pdf = PDF::loadView('repairInvoicePrint', ['pawnSumData' => $T_sumdata , 'pawnDetailsData' => $T_detailsdata, 'companyData' => $companyData]);

       // Save the PDF to a temporary file
       $pdfPath = storage_path('../public/assets/pdf/Repair_Invoice.pdf');
       // $pdfPath = storage_path('../pdf/Repair_Invoice.pdf');
       $pdf->save($pdfPath);

       // $pdfUrl = attach('pdf/Repair_Invoice.pdf');
       $pdfUrl = asset('assets/pdf/Repair_Invoice.pdf');

       return back()
       ->with('done','The Purchases Return has been added');
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

            $InvoiceSum = new TPurchasesReturnSum;
            $InvoiceSum = new TPurchasesReturnSum;
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

            foreach ($request -> purchase_inputs as $key=>$value){
                $InvoiceDetails = new TPurchasesReturnDetails;
                $ItemMovementDetails = new TItemMovement;

                $InvoiceDetails->Invoice_no=$value['invoice_no'];
                $InvoiceDetails->Invoice_date=$value['invoice_date'];
                // $InvoiceDetails->Item_category=$value['item_category'];
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
                $ItemMovementDetails->trans_code="PRN";
                $ItemMovementDetails->item_code=$value['item_code'];
                $ItemMovementDetails->qun_out=$value['qty'];
                $ItemMovementDetails->bc = auth()->user()->BC;
                $ItemMovementDetails->save();
                }

            return response()->json([
                'status'=>'success',

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
        $data = TPurchasesReturnSum::where('Invoice_no',$receiptNo)
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
        $data = TPurchasesReturnDetails::where('Invoice_no',$receiptNo)
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
