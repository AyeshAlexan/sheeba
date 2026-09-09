<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JobSheet;
use App\Models\Item;
use App\Models\branchDel;
use App\Models\Category;
use App\Models\Store;
use App\Models\TStockTransferSum;
use App\Models\TStockTransferDetails;
use App\Models\TItemMovement;

class StockTransferController extends Controller
{
    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TStockTransferSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);
        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();
        $Branch = Store::all();
        $itemDetails =Item::latest()->paginate(5);

         // get max Customer number code
         $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
         $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('stockTransfer')
        ->with("branchName" , $Branch)
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
        ->with("itemDetails", $itemDetails)
        ->with("maxInvoiceNo", $maxInvoice);
    }


    public function createtockTransfer(Request $request){
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

        $InvoiceSum = new TStockTransferSum;

        $InvoiceSum->Invoice_no = $request->invoice_no;
        $InvoiceSum->Invoice_date = $request->invoice_date;
        $InvoiceSum->Branch_Code = $request->Branch_Code;
        $InvoiceSum->Branch_Name = $request->Branch_Name;
        $InvoiceSum->Branch_Phone = $request->Branch_Phone;
        $InvoiceSum->Branch_Address = $request->Branch_Address;
        $InvoiceSum->Gross_Amount = $request->gross_amount;
        $InvoiceSum->Discount =  $request->discount;
        $InvoiceSum->Net_Amount =  $request->net_amount;
        $InvoiceSum->To_Branch = $request->Branch_Code;
        $InvoiceSum->Is_Purchased =0;
        $InvoiceSum->BC = auth()->user()->BC;
        $InvoiceSum->OC = auth()->user()->username;
        $InvoiceSum->save();

        foreach ($request -> inputs as $key=>$value){
        $InvoiceDetails = new TStockTransferDetails;
        $InvoiceDetails->Invoice_no=$value['invoice_no'];
        $InvoiceDetails->Invoice_date=$value['invoice_date'];
        // $InvoiceDetails->Item_category=$value['item_category'];
        $InvoiceDetails->Item_code=$value['item_code'];
        $InvoiceDetails->Item_description=$value['item_description'];
        $InvoiceDetails->QTY=$value['qty'];
        $InvoiceDetails->Unit_price=$value['unit_price'];
        $InvoiceDetails->Discount=$value['discount_val'];
        $InvoiceDetails->Net_value=$value['net_value'];
        $InvoiceDetails->Is_Purchased =0;
        $InvoiceDetails->To_Branch=$value['customer_nic'];
        $InvoiceDetails->OC= auth()->user()->username;
        $InvoiceDetails->BC= auth()->user()->BC;
        $InvoiceDetails->save();

        $ItemMovementDetails = new TItemMovement;
        $ItemMovementDetails->trans_no=$value['invoice_no'];
        $ItemMovementDetails->dDate=$value['invoice_date'];
        $ItemMovementDetails->trans_code="STOCK_TRANSFER";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_out=$value['qty'];
        // $ItemMovementDetails->From_store= $request->Branch_Code;
        // $ItemMovementDetails->To_Store= $request->To_Branch_Code;
        $ItemMovementDetails->storse_id= $request->Branch_Code;
        $ItemMovementDetails->bc = auth()->user()->BC;
        $ItemMovementDetails->save();


        $ItemMovementDetails = new TItemMovement;
        $ItemMovementDetails->trans_no=$value['invoice_no'];
        $ItemMovementDetails->dDate=$value['invoice_date'];
        $ItemMovementDetails->trans_code="STOCK_TRANSFER";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_in=$value['qty'];
        // $ItemMovementDetails->From_store= $request->To_Branch_Code;
        // $ItemMovementDetails->To_Store=0;
        $ItemMovementDetails->bc = auth()->user()->BC;
        $ItemMovementDetails->storse_id =  $request->To_Branch_Code;
        $ItemMovementDetails->save();



        }


        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;


        $companyData = Company::latest()->paginate(1);
        $invoiceInput_no = $request->invoice_no;
        $T_detailsdata = TStockTransferDetails::where('Invoice_no', $invoiceInput_no)
                        ->where('BC',$branch_code)
                        ->get();

        $T_sumdata = TStockTransferSum::where('Invoice_no', $invoiceInput_no)
                    ->where('BC',$branch_code)
                    ->get();

        $customer_nic = $request->Branch_Code;
        $T_customerdata = branchDel::where('bccode', $customer_nic)
                  ->get();

        // Generate the PDF content using a view
        $pdf = PDF::loadView('stocktranferPrint', [
            'pawnSumData' => $T_sumdata ,
            'customerData' => $T_customerdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData' => $companyData]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Repair_Invoice.pdf');
        // $pdfPath = storage_path('../pdf/Repair_Invoice.pdf');
        $pdf->save($pdfPath);

        // $pdfUrl = attach('pdf/Repair_Invoice.pdf');
        $pdfUrl = asset('public/assets/pdf/Repair_Invoice.pdf');

        return back()
        ->with('done','The Invoice has been added')
        ->with("pdfLink", $pdfUrl);
    }


 // ............search using ajax.................
    public function search(Request $request){
        $itemsData = Item::where('Item_description', 'like', '%'.$request->search_string.'%')
        ->orWhere('Item_code','like','%'.$request->search_string.'%')
        ->orderBy('Item_code','desc')
        ->paginate(20);

        if($itemsData->count() >= 1){
            return view('item_details_pagination_stock_t')->with("itemDetails",$itemsData)->render();
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
        $data = TStockTransferDetails::where('Invoice_no',$receiptNo)
                    ->where('BC',$branch_code)
                    ->get();
        if($data->count() != null){
            return view('invoice_find_tInvoiceDetails')
            // ->with("itemsDetails" , $itemsData)
            ->with('invoiceData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    // find invoice customer data
    public function findInvoiceCustomerData(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TStockTransferSum::where('Invoice_no',$receiptNo)
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


    public function destroy(Request $request)
    {
        Invoice::find($request->invoice_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

        public function GetBranchDeatails(Request $request)
        {
            $DRcode = $request->amount;
            $data = Store::where('Store_code', $DRcode)->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }


   public function GetBranchDeatailsTwo(Request $request)
    {
    $DRcode = $request -> amount;
   $data = Store::where('Store_code', $DRcode)->get();

    return response()->json([
        'status' => 'success',
        'data' => $data
    ]);
}


public function StockTranferReport(Request $request)
{
    $branch_code = auth()->user()->BC;
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $StoreCode = $request->input('storse_id');

    $storeDta = Store::all();

    $reciptsdata = TItemMovement::whereBetween('dDate', [$fromDate, $toDate])
        ->where('bc', $branch_code)
        ->where('storse_id', $StoreCode)
        ->orderBy('dDate', 'asc') // optional: to sort by date
        ->get(); // fetch all matching records

    return view('reports.stockTranferReport')
        ->with("storse_id", $StoreCode)
        ->with("storeDta", $storeDta)
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with('recipts', $reciptsdata);
}


}