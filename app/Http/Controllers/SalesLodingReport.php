<?php

namespace App\Http\Controllers;

use App\Models\MSalesman;
use App\Models\TInvoiceDeils;
use App\Models\TInvoiceSum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesLodingReport extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;
        $selected_salesman = $request->salesman;
        
        $salesman_data =MSalesman::all();
        $details_query = TInvoiceDeils::query();

        if($fromDate && $toDate) {
            $sum_query = TInvoiceSum::whereBetween('Invoice_date', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->where('Salesmen',$selected_salesman)
                    ->get();

            $invoice_no = $sum_query->pluck('Invoice_no')->toArray();

            $details_query = TInvoiceDeils::whereBetween('Invoice_date', [$fromDate, $toDate])
                ->whereIn('Invoice_no', $invoice_no)
                ->where('BC', $branch_code)
                ->select(
                    'Item_s_code',
                    'Item_description',
                    DB::raw("SUM(QTY) as total_qty")
                )
                ->groupBy('Item_s_code', 'Item_description')
                ->get();
        }

       return view('reports.sales_loding_report')
        ->with("item_details" , $details_query)
        ->with("salesman_data" , $salesman_data)
        ->with("salesman" , $selected_salesman)
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate);
    }
}