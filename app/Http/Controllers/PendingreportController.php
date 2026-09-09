<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\JobSheet; // Replace with your actual model
use App\Models\Datatables;

class PendingreportController extends Controller
{

public function index(Request $request)
{

    // Get search criteria from the form input
    $fromDate  = $request->input('from_date');
    $toDate = $request->input('to_date');

    $query = JobSheet::whereBetween('Date', [$fromDate, $toDate])->get();
    $fromDate  = $request->input('from_date');
    $toDate = $request->input('to_date');




    // Construct a query to filter records based on search criteria
    $query = JobSheet::query();


    if ($fromDate && $toDate) {
        $query = JobSheet::whereBetween('Date', [$fromDate, $toDate])

        ->where('Status', 'Pending')
            ->get();

    }



    // Calculate the sum of the desired column (e.g., 'amount_column')
    // $total = $query->sum('Amount');
    // $totalAmount = number_format($total, 2);
    // $int =  $query->sum('Interest');
    // $interest = number_format($int, 2);
    // // $TotalPawn = TPawnSum::count();




    return view('Pending_report')
    -> with("recipts", $query);
    // -> with("amount", $totalAmount)
    // -> with("Interest", $interest);
    //  ->with('TotalPawn',$TotalPawn);
}

}



