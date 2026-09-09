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
use App\Models\StockAdjuestmentSum;
use App\Models\StockAdjuestmentDetails;
use Illuminate\Support\Facades\DB;


class StockAdjuestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = StockAdjuestmentSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
                        
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();

        $itemDetails = Item::all();
            // where('BC', $branch_code)->get();

        // get max Customer number code
        $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        $storeDta = Store::all();
        
        return view('stockAdjuestment')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
        ->with("itemDetails", $itemDetails)
        ->with("maxInvoiceNo", $maxInvoice)
        ->with("storeDta" , $storeDta );
    }
    
        public function indexShow()
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = StockAdjuestmentSum::orderBy('Invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('Invoice_no');
                        
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $itemCategory = Category::all();

        
        $itemDetails = Item::join('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
        ->select(
            'items.Item_code', // Select the item code once
            'items.id', // Include the id column
            'items.Item_description', // Include any other necessary columns
            'items.saleprice',
            DB::raw('SUM(t_item_movements.qun_in) as total_qun_in'),
            DB::raw('SUM(t_item_movements.qun_out) as total_qun_out')
        )
        ->selectRaw('SUM(t_item_movements.qun_in) - SUM(t_item_movements.qun_out) AS QTY')
        ->groupBy('items.Item_code', 'items.id', 'items.Item_description','items.saleprice') // Group by all non-aggregated fields
        ->where('bc',$branch_code)
        ->havingRaw('QTY') // Use QTY directly from the selectRaw calculation
        ->get();
            // where('BC', $branch_code)->get();

        // get max Customer number code
        $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        $storeDta = Store::all();
        
        return view('stockAdjuestmentNew')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
        ->with("itemDetails", $itemDetails)
        ->with("maxInvoiceNo", $maxInvoice)
        ->with("storeDta" , $storeDta );
    }


    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddStockAdjuestment(Request $request){
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
           'inputs.*.qtyOut' => ' The QTY field is required',
           'inputs.*.unit_price' => ' The Unit Price field is required',
           'inputs.*.net_value' => ' The Net Value field is required'
       ]);

       $InvoiceSum = new StockAdjuestmentSum;
       
       $InvoiceSum->Invoice_no = $request->invoice_no;
       $InvoiceSum->Invoice_date = $request->invoice_date;
       $InvoiceSum->Store_code =  $request->Store_code;
       $InvoiceSum->Amount =  $request->Amount;
       $InvoiceSum->BC = auth()->user()->BC;
       $InvoiceSum->OC = auth()->user()->username;
       $InvoiceSum->save();




       foreach ($request -> inputs as $key=>$value){
       $InvoiceDetails = new StockAdjuestmentDetails;
       $ItemMovementDetails = new TItemMovement;

       $InvoiceDetails->Store_code=$value['Store_code'];
       $InvoiceDetails->Invoice_no=$value['invoice_no'];
       $InvoiceDetails->Invoice_date=$value['invoice_date'];
       $InvoiceDetails->Item_code=$value['item_code'];
       $InvoiceDetails->Item_description=$value['item_description'];
       $InvoiceDetails->QTY=$value['qty'];
       $InvoiceDetails->qtyOut=$value['qtyOut'];
       $InvoiceDetails->Unit_price=$value['unit_price'];
       $InvoiceDetails->Net_value=$value['net_value'];
       $InvoiceDetails->OC= auth()->user()->username;
       $InvoiceDetails->BC= auth()->user()->BC;
       $InvoiceDetails->save();

       $ItemMovementDetails->trans_no=$value['invoice_no'];
       $ItemMovementDetails->dDate=$value['invoice_date'];
       $ItemMovementDetails->trans_code="STOCK_ADJUSTMENT";
       $ItemMovementDetails->item_code=$value['item_code'];
       $ItemMovementDetails->qun_in=$value['qty'];
       $ItemMovementDetails->qun_out=$value['qtyOut'];
       $ItemMovementDetails->bc= auth()->user()->BC;
       $ItemMovementDetails->save();
       }

       $branch_code = auth()->user()->BC;
       $user_name = auth()->user()->username;
       
       $companyData = Company::latest()->paginate(1);

       $invoiceInput_no = $request->invoice_no;
       $T_detailsdata = StockAdjuestmentDetails::where('Invoice_no', $invoiceInput_no)
                       ->where('BC',$branch_code)
                       ->get();
       
       $T_sumdata = StockAdjuestmentSum::where('Invoice_no', $invoiceInput_no)
                   ->where('BC',$branch_code)
                   ->get();

       // Generate the PDF content using a view
       $pdf = PDF::loadView('repairstockAdjustmentPrint', ['pawnSumData' => $T_sumdata , 'pawnDetailsData' => $T_detailsdata, 'companyData' => $companyData]);

       // Save the PDF to a temporary file
       $pdfPath = storage_path('../public/assets/pdf/Repair_Invoice.pdf');
       // $pdfPath = storage_path('../pdf/Repair_Invoice.pdf');
       $pdf->save($pdfPath);

       // $pdfUrl = attach('pdf/Repair_Invoice.pdf');
       $pdfUrl = asset('public/assets/pdf/Repair_Invoice.pdf');

       return back()
       ->with('done','The Opening Stock has been added');
       // ->with("pdfLink", $pdfUrl);
   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
          public function setItemDescriptionShow(Request $request)
        {
            $Item_code = $request->Item_code;

            $data = Item::join('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
                ->select(
                    'items.Item_code',
                    'items.id',
                    'items.Item_description',
                    'items.saleprice',
                    DB::raw('SUM(t_item_movements.qun_in) as total_qun_in'),
                    DB::raw('SUM(t_item_movements.qun_out) as total_qun_out')
                )
                ->selectRaw('SUM(t_item_movements.qun_in) - SUM(t_item_movements.qun_out) AS QTY')
                ->groupBy('items.Item_code', 'items.id', 'items.Item_description', 'items.saleprice')
                ->havingRaw('QTY') // Optional: you might want to add a condition like `> 0`
                ->where('items.Item_code', $Item_code)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
       public function Store_StockAdjuestment(Request $request)
        {
            // Save Invoice Summary
            $InvoiceSum = new StockAdjuestmentSum;
            $InvoiceSum->Invoice_no = $request->invoice_no;
            $InvoiceSum->Invoice_date = $request->invoice_date;
            $InvoiceSum->Store_code = $request->Store_code;
            
            // Calculate total amount from net values
            $totalAmount = 0;
            foreach ($request->net_value as $val) {
                $totalAmount += (float)$val;
            }
    
            $InvoiceSum->Amount = $totalAmount;
            $InvoiceSum->BC = auth()->user()->BC;
            $InvoiceSum->OC = auth()->user()->username;
            $InvoiceSum->save();
    
            // Loop through each item row
            $count = count($request->item_code);
    
            for ($i = 0; $i < $count; $i++) {
                $InvoiceDetails = new StockAdjuestmentDetails;
                $ItemMovementDetails = new TItemMovement;
    
                $InvoiceDetails->Store_code = $request->Store_code;
                $InvoiceDetails->Invoice_no = $request->invoice_no;
                $InvoiceDetails->Invoice_date = $request->invoice_date;
                $InvoiceDetails->Item_code = $request->item_code[$i];
                $InvoiceDetails->Item_description = $request->item_description[$i];
                $InvoiceDetails->QTY = $request->qty[$i];
                $InvoiceDetails->qtyOut = $request->qtyOut[$i];
                $InvoiceDetails->Unit_price = $request->saleprice[$i];
                $InvoiceDetails->Net_value = $request->net_value[$i];
                $InvoiceDetails->OC = auth()->user()->username;
                $InvoiceDetails->BC = auth()->user()->BC;
                $InvoiceDetails->save();
    
                // Save item movement
            $ItemMovementDetails->trans_no = $request->invoice_no;
            $ItemMovementDetails->dDate = $request->invoice_date;
            $ItemMovementDetails->trans_code = "STOCK_ADJUSTMENT";
            $ItemMovementDetails->item_code = $request->item_code[$i];
            $ItemMovementDetails->qun_in =  $request->qtyOut[$i];
            $ItemMovementDetails->Stock_adjuestment = $request->Stock_adjuestment[$i];
            $ItemMovementDetails->bc = auth()->user()->BC;
            $ItemMovementDetails->save();
            }
    
            return redirect()->back()->with('success', 'Stock Adjustment Saved Successfully!');
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