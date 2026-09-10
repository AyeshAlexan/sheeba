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
use App\Models\TSalesReturnSum;
use App\Models\TSalesReturnDetails;
use App\Models\TItemMovement;
use App\Models\TCusSaleTrance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReturnController extends Controller
{

    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = TSalesReturnSum::orderBy('Invoice_no', 'desc')
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

        return view('sales_return')
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

public function createSalesReturn(Request $request)
{
    // 1. Validate both top-level fields and nested array items
    $request->validate([
        'invoice_no'       => 'required|string',
        'invoice_date'     => 'required|date',
        'customer_nic'     => 'nullable|string',
        'customer_name'    => 'required|string',
        'gross_amount'     => 'required|numeric',
        'net_amount'       => 'required|numeric',
        'inputs'           => 'required|array|min:1',
        'inputs.*.invoice_no'   => 'required',
        'inputs.*.invoice_date' => 'required',
        'inputs.*.item_code'    => 'required',
        'inputs.*.qty'          => 'required|numeric',
        'inputs.*.unit_price'   => 'required|numeric',
        'inputs.*.net_value'    => 'required|numeric'
    ]);

    try {
        DB::beginTransaction();

        $user = Auth::user();

        // 2. Save Summary Header
        $InvoiceSum = new TSalesReturnSum;
        $InvoiceSum->Invoice_no     = $request->invoice_no;
        $InvoiceSum->Invoice_date   = $request->invoice_date;
        $InvoiceSum->Customer_NIC   = $request->customer_nic;
        $InvoiceSum->Customer_Name  = $request->customer_name;
        $InvoiceSum->Customer_Phone = $request->customer_phone;
        $InvoiceSum->Gross_Amount   = $request->gross_amount;
        $InvoiceSum->Discount       = $request->discount ?? 0;
        $InvoiceSum->Net_Amount     = $request->net_amount;
        $InvoiceSum->Cash_Pay       = $request->cash_payment ?? 0;
        $InvoiceSum->Credite        = $request->credite_payment ?? 0;
        $InvoiceSum->Cheque         = $request->cheque_payment ?? 0;
        $InvoiceSum->BC             = $user->BC;
        $InvoiceSum->OC             = $user->username;
        $InvoiceSum->save();

        // 3. Save Line Items & Item Movement
        foreach ($request->inputs as $value) {
            $InvoiceDetails = new TSalesReturnDetails;
            $InvoiceDetails->Invoice_no       = $value['invoice_no'];
            $InvoiceDetails->Invoice_date     = $value['invoice_date'];
            $InvoiceDetails->Item_code        = $value['item_code'];
            $InvoiceDetails->Item_description = $value['item_description'] ?? '';
            $InvoiceDetails->QTY              = $value['qty'];
            $InvoiceDetails->Unit_price       = $value['unit_price'];
            $InvoiceDetails->Discount         = $value['discount_val'] ?? 0;
            $InvoiceDetails->Net_value        = $value['net_value'];
            $InvoiceDetails->OC               = $user->username;
            $InvoiceDetails->BC               = $user->BC;
            $InvoiceDetails->save();

            $ItemMovementDetails = new TItemMovement;
            $ItemMovementDetails->trans_no   = $value['invoice_no'];
            $ItemMovementDetails->dDate      = $value['invoice_date'];
            $ItemMovementDetails->trans_code = "SALES_RETURN";
            $ItemMovementDetails->item_code  = $value['item_code'];
            $ItemMovementDetails->qun_in     = $value['qty'];
            $ItemMovementDetails->bc         = $user->BC;
            $ItemMovementDetails->save();
        }

        // 4. Ledger Postings

        $creditTrance = new TCusSaleTrance;
        $creditTrance->no            = $request->invoice_no;
        $creditTrance->customer      = $request->customer_nic;
        $creditTrance->dr_trnce_code = "SALES_RETURN";
        $creditTrance->dr_trnce_no   = $request->invoice_no;
        $creditTrance->cr_trnce_code = "SALES_RETURN";
        $creditTrance->cr_trnce_no   = $request->invoice_no;
        $creditTrance->dr_amount     = $request->cash_payment ?? 0;
        $creditTrance->cr_amount     = $request->credite_payment ?? 0;
        $creditTrance->trance_type   = "SALES_RETURN";
        $creditTrance->trance_no     = $request->invoice_no;
        $creditTrance->Display_Ref   = $request->invoice_no;
        $creditTrance->dDate         = $request->invoice_date;
        $creditTrance->BC            = $user->BC;
        $creditTrance->OC            = $user->username;
        $creditTrance->save();

        DB::commit();

        return back()
            ->with('done', 'Sales Return added successfully')
            ->with('print_invoice_no', $request->invoice_no);

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
    public function printInvoice($invoice_no)
    {
        $branch_code = Auth::user()->BC;

        // Use first() to get an object, not a collection or paginator
        $companyData = Company::latest()->first();

        $T_detailsdata = TSalesReturnDetails::where('Invoice_no', $invoice_no)
                        ->where('BC', $branch_code)
                        ->get();

        $T_sumdata = TSalesReturnSum::where('Invoice_no', $invoice_no)
                        ->where('BC', $branch_code)
                        ->get();

        return view('repairReturnInvoicePrint', [
            'pawnSumData' => $T_sumdata,
            'pawnDetailsData' => $T_detailsdata,
            'companyData' => $companyData
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
        $data = TSalesReturnSum::where('Invoice_no',$receiptNo)
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
  // Inside your Controller
public function searchCustomers(Request $request) {
    $query = $request->get('query');

    $customers = DB::table('customers')
        ->where('First_name', 'LIKE', "%{$query}%")
        ->orWhere('Address_1', 'LIKE', "%{$query}%")
        ->orWhere('Code', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

    $output = '';
    if($customers->count() > 0) {
        foreach($customers as $row) {
            $output .= '
            <a href="javascript:void(0)" class="list-group-item list-group-item-action customer-item"
               data-code="'.$row->Code.'">
               <div class="d-flex justify-content-between">
                   <strong class="mb-1">'.$row->First_name.' '.$row->Last_name.'</strong>
                   <small class="text-muted">'.$row->Code.'</small>
               </div>
               <small class="text-muted d-block">'.$row->Address_1.'</small>
            </a>';
        }
    } else {
        $output .= '<div class="list-group-item">No customers found.</div>';
    }
    return $output;
}


    public function destroy(Request $request)
    {
        Invoice::find($request->invoice_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }
}