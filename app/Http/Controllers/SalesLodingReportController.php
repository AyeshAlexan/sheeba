<?php

namespace App\Http\Controllers;
use App\Models\MSalesman;
use App\Models\TInvoiceDeils;
use App\Models\TInvoiceSum;
use App\Models\TWithoutVatSalesDetails;
use App\Models\TSalesReturnDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalesLodingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Optional: Get parameters from the request to filter the data dynamically
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $selected_salesman = $request->salesman;

        
        // Free issues details query
        $freeIssuesQuery = DB::table('t_without_vat_sales_details')
            ->select('Item_code', 'Item_description', DB::raw('SUM(QTY) AS total_qty'),
             DB::raw('SUM(Free_Issues) AS total_Free_Issues'),
             DB::raw("'Free Issue' AS source"))
            ->whereNotNull('Invoice_no')
            ->whereNotNull('Item_description')
            ->whereNotNull('QTY')
            ->whereBetween('Invoice_date', [$fromDate, $toDate])
            ->groupBy('Item_code', 'Item_description');
        
        // Invoice details query
        $invoiceDetailsQuery = DB::table('t_invoice_deils')
            ->select('Item_code', 'Item_description', DB::raw('SUM(QTY) AS total_qty'), 
            DB::raw('SUM(Free_Issues) AS total_Free_Issues'),
            DB::raw("'Invoice' AS source"))
            ->whereNotNull('Invoice_no')
            ->whereNotNull('Item_description')
            ->whereBetween('Invoice_date', [$fromDate, $toDate])
            ->whereNotNull('QTY')
            ->groupBy('Item_code', 'Item_description');
        
        // Combine all queries using UNION
        $combinedQuery = $freeIssuesQuery
            ->union($invoiceDetailsQuery)
            ->get();  // Execute the query and get the results
    

        $salesman_data =MSalesman::all();   
        // Return the view with the combined query results passed to the view
        return view('reports.sales_loding_report')
            ->with("salesman_data" , $salesman_data)
            ->with("combined_query", $combinedQuery);  // pass combined query results to the view
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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