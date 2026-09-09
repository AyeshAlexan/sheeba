<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TRedeemSum; // Replace with your actual model


class RedeemreportController extends Controller
{
    public function redeem(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $redeem= TRedeemSum::whereBetween('Redeem_Date', [$fromDate, $toDate])->get();

        return view('Redeemreport', compact('redeem'));
    }
}


