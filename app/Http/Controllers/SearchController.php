<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPawnSum; // Replace with your actual model

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $recipts = TPawnSum::whereBetween('Receipt_Date', [$fromDate, $toDate])->get();

        return view('search_results', compact('recipts'));
    }
}
