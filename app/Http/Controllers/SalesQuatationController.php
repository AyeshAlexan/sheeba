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
use App\Models\Category;
use App\Models\TSalesOrderSum;
use App\Models\TSalesOrderDetails;
use App\Models\TItemMovement;
use App\Models\TItemSerialMovement;
use App\Models\TAccountTrans;
use App\Models\TCusSaleTrance;

class SalesQuatationController extends Controller
{

    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TSalesOrderSum::orderBy('Invoice_no', 'desc')
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

        return view('sales_quatation')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
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

    public function createQuatation(Request $request){
         $request->validate([

            'inputs.*.invoice_no' => ' required',
            'inputs.*.invoice_date' => ' required',
            // 'inputs.*.item_category' => ' required',
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

        $InvoiceSum = new TSalesOrderSum;

        $InvoiceSum->Invoice_no = $request->invoice_no;
        $InvoiceSum->Invoice_date = $request->invoice_date;
        $InvoiceSum->Customer_NIC = $request->customer_nic;
        $InvoiceSum->Customer_Name = $request->customer_name;
        $InvoiceSum->Customer_Phone = $request->customer_phone;
        // $InvoiceSum->Amount = $request->total_amount;
        // $InvoiceSum->Advance = $request->paid_amount;
        // $InvoiceSum->Balance = $InvoiceSum->Amount - $InvoiceSum->Advance;
        $InvoiceSum->Gross_Amount = $request->gross_amount;
        $InvoiceSum->Discount =  $request->discount;
        $InvoiceSum->Net_Amount =  $request->net_amount;
        $InvoiceSum->Cash_Pay =  $request->cash_payment;
        $InvoiceSum->Credite =  $request->credite_payment;
        $InvoiceSum->Cheque =  $request->cheque_payment;
        $InvoiceSum->serial_number =  $request->serial_numbers_text;
        $InvoiceSum->BC = auth()->user()->BC;
        $InvoiceSum->OC = auth()->user()->username;
        $InvoiceSum->save();

        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type = $request->invoice_no;
        $AccountTrans->trance_type="SALES_QUA";
        $AccountTrans->Ddate = $request->invoice_date;
        $AccountTrans->AccCode = $request->customer_nic;
        $AccountTrans->Description = $request->item_description;
        $AccountTrans->dr_amount="0";
        $AccountTrans->cr_amount = $request->cash_payment;
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();

        $TCusSaleTrance = new TCusSaleTrance;
        $TCusSaleTrance->no = $request->invoice_no;
        $TCusSaleTrance->customer = $request->customer_nic;
        $TCusSaleTrance->dr_trnce_code="SALES_QUA";
        $TCusSaleTrance->dr_trnce_no = $request->invoice_no;
        $TCusSaleTrance->cr_trnce_code ="SALES_QUA";
        $TCusSaleTrance->cr_trnce_no = $request->invoice_no;
        $TCusSaleTrance->dr_amount= "0";
        $TCusSaleTrance->cr_amount = $request->cash_payment;
        $TCusSaleTrance->trance_type ="SALES_QUA";
        $TCusSaleTrance->trance_no =  $request->invoice_no;
        $TCusSaleTrance->Display_Ref = $request->invoice_no;
        $TCusSaleTrance->dDate =  $request->invoice_date;
        $TCusSaleTrance->BC = auth()->user()->BC;
        $TCusSaleTrance->OC = auth()->user()->username;
        $TCusSaleTrance->save();

        $TCusSaleTrance = new TCusSaleTrance;
        $TCusSaleTrance->no = $request->invoice_no;
        $TCusSaleTrance->customer = $request->customer_nic;
        $TCusSaleTrance->dr_trnce_code="SALES_QUA";
        $TCusSaleTrance->dr_trnce_no = $request->invoice_no;
        $TCusSaleTrance->cr_trnce_code ="SALES_QUA";
        $TCusSaleTrance->cr_trnce_no = $request->invoice_no;
        $TCusSaleTrance->dr_amount= $request->cash_payment;
        $TCusSaleTrance->cr_amount = "0";
        $TCusSaleTrance->trance_type ="SALES_QUA";
        $TCusSaleTrance->trance_no =  $request->invoice_no;
        $TCusSaleTrance->Display_Ref = $request->invoice_no;
        $TCusSaleTrance->dDate =  $request->invoice_date;
        $TCusSaleTrance->BC = auth()->user()->BC;
        $TCusSaleTrance->OC = auth()->user()->username;
        $TCusSaleTrance->save();




        foreach ($request -> inputs as $key=>$value){
        $InvoiceDetails = new TSalesOrderDetails;
        $ItemMovementDetails = new TItemMovement;

        $InvoiceDetails->Invoice_no=$value['invoice_no'];
        $InvoiceDetails->Invoice_date=$value['invoice_date'];
        $InvoiceDetails->Item_code=$value['item_code'];
        $InvoiceDetails->Item_s_code=$value['item_s_code'];
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
        $ItemMovementDetails->trans_code="SALES_QUA";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_out=$value['qty'];
        $ItemMovementDetails->bc= auth()->user()->BC;
        $ItemMovementDetails->save();

        }

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $companyData = Company::latest()->paginate(1);

        $invoiceInput_no = $request->invoice_no;
        $T_detailsdata = TSalesOrderDetails::where('Invoice_no', $invoiceInput_no)
                        ->where('BC',$branch_code)
                        ->get();

        $T_sumdata = TSalesOrderSum::where('Invoice_no', $invoiceInput_no)
                    ->where('BC',$branch_code)
                    ->get();

        $customer_nic = $request->customer_nic;
        $T_customerdata = Customer::where('NIC', $customer_nic)
                                    ->get();

        // Generate the PDF content using a view
        $pdf = PDF::loadView('sales_quatation_print', [
            'pawnSumData' => $T_sumdata ,
            'customerData' => $T_customerdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData' => $companyData]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Sales_Quatation.pdf');
        // $pdfPath = storage_path('../pdf/Repair_Invoice.pdf');
        $pdf->save($pdfPath);

        // $pdfUrl = attach('pdf/Repair_Invoice.pdf');
        $pdfUrl = asset('public/assets/pdf/Sales_Quatation.pdf');

        return back()
        ->with('done','The Sales Orders has been added')
        ->with("pdfLink", $pdfUrl);
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
        $data = TSalesReturnDetails::where('Invoice_no',$receiptNo)
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
        $data = TSalesOrderSum::where('Invoice_no',$receiptNo)
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
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Request $request)
    {
        Invoice::find($request->invoice_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }
}


