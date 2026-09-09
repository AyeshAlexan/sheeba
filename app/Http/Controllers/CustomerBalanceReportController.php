<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TCusSaleTrance;
use App\Models\Customer;

class CustomerBalanceReportController extends Controller
{
    public function index(Request $request){

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerCode = $request->input('customer');
        $branchCode = auth()->user()->BC;
        $supName="";

        $query = TCusSaleTrance::select(
            't_cus_sale_trances.customer',
            DB::raw('SUM(t_cus_sale_trances.dr_amount) as total_dr_amount'),
            DB::raw('SUM(t_cus_sale_trances.cr_amount) as total_cr_amount'),
            'customers.Code',
            'customers.First_name'
        )
        ->join('customers', 'customers.Code', '=', 't_cus_sale_trances.customer')
        ->groupBy('t_cus_sale_trances.customer', 'customers.Code', 'customers.First_name');

        if ($fromDate && $toDate && $customerCode) {
            $query  ->whereBetween('dDate', [$fromDate, $toDate])
                    ->where('t_cus_sale_trances.customer',$customerCode);
            $cusName = $query->pluck('customers.First_name')->first();
        }
        else if ($fromDate && $toDate) {
            $query  ->whereBetween('dDate', [$fromDate, $toDate]);
        }else if ($customerCode) {
            $query  ->where('t_cus_sale_trances.customer',$customerCode);
            $cusName = $query->pluck('customers.First_name')->first();
        }

        $customer_details = $query->get();

        return view('reports.customer_balance_report')
        ->with("customerData", $customer_details)
        ->with("supName", $supName)
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
       ;
    }
}
