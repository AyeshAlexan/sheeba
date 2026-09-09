<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MSalesman;
use App\Models\branchDel;
use App\Models\TInvoiceSum;
use App\Models\TInvoiceDeils;
use Illuminate\Support\Facades\DB;

class MSalesmanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branchDel =MSalesman::all();
        return view('SalesMan')
        ->with("Branchdetails" , $branchDel);

        
    }

    function add_branch(Request $request){
        $request->validate([
            'code'=>'required | max:50 ',
            'name'=>'required | max:100 ',
            'address'=>'required | max:1000 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'contact2'=>['max:10'],
            'date'=>'required',
        ]);
        // $request->validate(
        //     [
        //         'up_bccode'=>'required | max:5 | unique:branch_dels',
        //         'up_name'=>'required | max:20 ',
        //         'up_address'=>'required | max:40 ',
        //         'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
        //         'up_contact2'=>['max:10'],
        //         'up_date'=>'required',
        //     ],
        //     [
        //         'up_bccode.required' => 'BC Code is Required',
        //         'up_bccode.unique' => 'Branch Already Exists',
        //         'up_bccode.max' => 'BC Code Maximum Characters is 5',
        //         'up_name.required' => 'Branch Name is Required',
        //         'up_name.max' => 'Branch Name Maximum Characters is 20',
        //     ]
        // );

        $branch = new MSalesman;
        $branch->code = $request->code;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->contact1 = $request->contact1;
        $branch->contact2 = $request->contact2;
        $branch->date = $request->date;
        $branch->save();
        return redirect()->back()->with("Add_branch",$branch);
    }


// .....................Add Branch using Ajax.........................
    public function createSalesMan(Request $request)
    {
        $request->validate([
            'code'=>'required | max:50 ',
            'name'=>'required | max:100 ',
            'address'=>'required | max:1000 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'contact2'=>['max:10'],
            'date'=>'required',
        ]);

        $branch = new MSalesman;
        $branch->code = $request->code;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->contact1 = $request->contact1;
        $branch->contact2 = $request->contact2;
        $branch->date = $request->date;
        $branch->save();
        return response()->json([
            'status'=>'success',
        ]);
    }


public function SalesmanInvoiceReport(Request $request)
{
    $branchDel = MSalesman::all();
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $selected_salesman = $request->Salesmen;
    $branch_code = auth()->user()->BC;

    $invoiceData = collect(); // default empty

    if ($fromDate && $toDate) {
        $invoiceData = DB::table('t_without_vat_sales_details')
            ->join('t_without_vat_sales_sums', 't_without_vat_sales_details.Invoice_no', '=', 't_without_vat_sales_sums.Invoice_no')
            ->whereBetween('t_without_vat_sales_details.Invoice_date', [$fromDate, $toDate])
            ->where('t_without_vat_sales_details.BC', $branch_code)
            ->where('t_without_vat_sales_sums.Salesmen', $selected_salesman)
            ->groupBy('t_without_vat_sales_details.Item_code', 't_without_vat_sales_details.Item_description') // FIXED
            ->select(
                't_without_vat_sales_details.Item_code',
                't_without_vat_sales_details.Item_description',
                DB::raw('SUM(t_without_vat_sales_details.QTY) as Total_Qty'),
                DB::raw('SUM(t_without_vat_sales_details.Free_Issues) as Total_Free'),
                DB::raw('AVG(t_without_vat_sales_details.Unit_price) as Avg_Unit_Price'),
                DB::raw('SUM(t_without_vat_sales_details.Discount) as Total_Line_Discount'),
                DB::raw('SUM(t_without_vat_sales_details.Net_value) as Total_Net_Value'),
                DB::raw('MIN(t_without_vat_sales_details.Invoice_date) as First_Invoice_Date'),
                DB::raw('MAX(t_without_vat_sales_details.Invoice_date) as Last_Invoice_Date'),

                // Aggregate sums from t_invoice_sums
                DB::raw('SUM(t_without_vat_sales_sums.Gross_Amount) as Total_Invoice_Amount'),
                DB::raw('SUM(t_without_vat_sales_sums.Discount) as Total_Invoice_Discount'),
                DB::raw('SUM(t_without_vat_sales_sums.Net_Amount) as Total_Invoice_Net_Amount')
            )
            ->orderBy('t_without_vat_sales_details.Item_code')
            ->get();
    }

    return view('reports.salesmanInvoiceReport', [
        'invoice' => $invoiceData,
        'salesmen' => $branchDel,
        'salesman' => $selected_salesman,
        'fromDate' => $fromDate,
        'toDate' => $toDate
    ]);
}




  public function SalesmanInvoiceSumReport(Request $request)
{
    // Get all salesmen
    $salesmenList = MSalesman::all();

    // Get input values
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $selectedSalesman = $request->input('Salesmen'); // Make sure your input name is 'Salesmen'
    $branchCode = auth()->user()->BC;

    // Build the query
    $query = DB::table('t_without_vat_sales_sums')
        ->where('BC', $branchCode);

    // Filter by salesman if selected
    if ($selectedSalesman) {
        $query->where('Salesmen', $selectedSalesman);
    }

    // Filter by date range if provided
    if ($fromDate) {
        $query->whereDate('Invoice_date', '>=', $fromDate);
    }
    if ($toDate) {
        $query->whereDate('Invoice_date', '<=', $toDate);
    }

    // Get the data
    $invoiceData = $query->get();

    // Return the view with data
    return view('reports.SalesmanInvoiceSumReport', [
        'invoice' => $invoiceData,
        'salesmen' => $salesmenList,
        'salesman' => $selectedSalesman,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
    ]);
}


}