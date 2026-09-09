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
use App\Models\Store;
use App\Models\Category;
use App\Models\TItemMovement;
use App\Models\TOpeningSum;
use App\Models\TOpeningDetails;

class OpeningStockController extends Controller
{

    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TOpeningSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
                        
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();

        $itemDetails =Item::all();
            // where('BC', $branch_code)->get();

        // get max Customer number code
        $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        $storeDta = Store::all();
        
        return view('openingStock')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
        ->with("itemDetails", $itemDetails)
        ->with("maxInvoiceNo", $maxInvoice)
        ->with("storeDta" , $storeDta );
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


    public function createOpeningStock(Request $request){


        $request->validate([
            'invoice_no'=>'required ',
            'storse_id'=>'required ',
        ]);


         $request->validate([
           
            'inputs.*.invoice_no' => ' required',
            'inputs.*.invoice_date' => ' required',
            // 'inputs.*.storse_id' => ' required',
            'inputs.*.item_code' => ' required',
            // 'inputs.*.item_description' => ' required',
            'inputs.*.qty' => ' required',
            'inputs.*.unit_price' => ' required',
            'inputs.*.net_value' => ' required'
        ],[
            
            'inputs.*.invoice_no' => ' The Invoice No field is required',
            'inputs.*.invoice_date' => ' The Invoice Date field is required',
            // 'inputs.*.item_category' => ' The Item Category field is required',
            'inputs.*.item_code' => ' The Item Code field is required',
            'inputs.*.item_description' => ' The Item Description field is required',
            'inputs.*.qty' => ' The QTY field is required',
            'inputs.*.unit_price' => ' The Unit Price field is required',
            'inputs.*.net_value' => ' The Net Value field is required'
        ]);

        $InvoiceSum = new TOpeningSum;
        
        $InvoiceSum->Invoice_no = $request->invoice_no;
        $InvoiceSum->Invoice_date = $request->invoice_date;
        $InvoiceSum->Store_code =  $request->Store_code;
        $InvoiceSum->Amount =  $request->Amount;
        $InvoiceSum->BC = auth()->user()->BC;
        $InvoiceSum->OC = auth()->user()->username;
        $InvoiceSum->save();




        foreach ($request -> inputs as $key=>$value){
        $InvoiceDetails = new TOpeningDetails;
        $ItemMovementDetails = new TItemMovement;

        $InvoiceDetails->Store_code=$value['Store_code'];
        $InvoiceDetails->Invoice_no=$value['invoice_no'];
        $InvoiceDetails->Invoice_date=$value['invoice_date'];
        $InvoiceDetails->Item_code=$value['item_code'];
        $InvoiceDetails->Item_description=$value['item_description'];
        $InvoiceDetails->QTY=$value['qty'];
        $InvoiceDetails->Unit_price=$value['unit_price'];
        $InvoiceDetails->Net_value=$value['net_value'];
        $InvoiceDetails->OC= auth()->user()->username;
        $InvoiceDetails->BC= auth()->user()->BC;
        $InvoiceDetails->save();

        $ItemMovementDetails->trans_no=$value['invoice_no'];
        $ItemMovementDetails->dDate=$value['invoice_date'];
        $ItemMovementDetails->trans_code="OPS";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_in=$value['qty'];
        $ItemMovementDetails->bc= auth()->user()->BC;
        $ItemMovementDetails->storse_id =  $request->storse_id;
        $ItemMovementDetails->save();
        }

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        
        $companyData = Company::latest()->paginate(1);

        $invoiceInput_no = $request->invoice_no;
        $T_detailsdata = TOpeningDetails::where('Invoice_no', $invoiceInput_no)
                        ->where('BC',$branch_code)
                        ->get();
        
        $T_sumdata = TOpeningSum::where('Invoice_no', $invoiceInput_no)
                    ->where('BC',$branch_code)
                    ->get();

        // Generate the PDF content using a view
        //$pdf = PDF::loadView('repairInvoicePrint', ['pawnSumData' => $T_sumdata , 'pawnDetailsData' => $T_detailsdata, 'companyData' => $companyData]);

        // Save the PDF to a temporary file
        //$pdfPath = storage_path('../public/assets/pdf/Repair_Invoice.pdf');
        // $pdfPath = storage_path('../pdf/Repair_Invoice.pdf');
       // $pdf->save($pdfPath);

        // $pdfUrl = attach('pdf/Repair_Invoice.pdf');
      //  $pdfUrl = asset('public/assets/pdf/Repair_Invoice.pdf');

        return back()
        ->with('done','The Opening Stock has been added');
        // ->with("pdfLink", $pdfUrl);
    }



    public function create(Request $request)
    {
        $request->validate([
            'invoice_no'=>'required | max:10 | unique:invoices',
            'job_no'=>'max:20 ',
            'invoice_date'=>'required',
            'customer_name'=>'max:80 ',
            'customer_phone'=>['required','max:10', 'regex:/^0\d{9,}$/'] ,
            'brand'=>'max:40',
            'device_model'=>'max:40 ',
            'imei_number'=>'max:40',
            'status'=>'max:40 ',
        ]);
        $invoice = new Invoice();
       
        $invoice->Invoice_no=$request->invoice_no;
        $invoice->Invoice_date=$request->invoice_date;
        $invoice->Delivery_date=$request->completed_on;
        $invoice->Reported_date=$request->receipt_date;
        $invoice->Technician="";
        $invoice->Customer_NIC="";
        $invoice->Customer_Name=$request->customer_name;
        $invoice->Customer_Phone=$request->customer_phone;
        $invoice->Brand=$request->brand;
        $invoice->Device_Model=$request->device_model;
        $invoice->IMEI_Number=$request->imei_number;
        $invoice->Status='Complete';
        $invoice->Item="";
        $invoice->Problem="";
        $invoice->Amount=$request->amount;
        $invoice->Advance=$request->advance;
        $invoice->Balance=$request->balance;
        $invoice->Serial_Number="";
        $invoice->Password="";
        $invoice->Product_Configuration="";
        $invoice->Problem_Reported="";
        $invoice->Product_Condition="";
        $invoice->OC=$request->operator;
        $invoice->BC="";
        $invoice->save();

        $job_no = $request->job_no;
        JobSheet::where('Job_no', $job_no)->update([
            'Status'=>"Complete",
        ]);

        return response()->json([
            'status'=>'success',
            // 'telNo'=> $CusData,
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

    // find invoice details
    public function findInvoice(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        $receiptNo = $request->search_receipt_no;
        $data = TOpeningDetails::where('Invoice_no',$receiptNo)
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
        $data = TOpeningSum::where('Invoice_no',$receiptNo)
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


    public function show($id)
    {
        //
    }


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
    public function GetStore(Request $request){
        $Storecode = $request -> category;
        $data = Store::where('Store_code',$Storecode)->get();

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
}