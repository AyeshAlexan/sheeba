<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\TOpeningHirePurchaseSum;
use App\Models\TPurchasesSum;
use App\Models\TOpeningSum;
use App\Models\TItemMovement;

use Illuminate\Http\Request;

class HirePuruchaseDashboardController extends Controller
{
    public function index()
    {


        $TotalPawn = TOpeningSum::count();
        $TotalRedeem = TPurchasesSum::count();
        $OpeningHirePurchaseSum = TOpeningHirePurchaseSum::count();
        $Pawningpayemt = TOpeningSum::sum('Amount');
        $Redeempayment = TPurchasesSum::sum('Net_Amount');
        $InvoiceSum = TOpeningHirePurchaseSum::sum('net_amount');
        // $Interest = TPawnSum::sum('Interest');

        // //Showing Pawning Customer Details in the Home page
        // $Pawningdetails = TPawnSum::all();

        // //Showing Pawning Customer Details in the Home page
        $QtyIn = TItemMovement::sum('qun_in');
        $QtyOut = TItemMovement::sum('qun_out');

        return view('HirePuruchaseDashboard')
        // , compact('TotalPawn','TotalRedeem','Pawningpayemt','Redeempayment','Interest'))
        //Showing Customer Details in the Home page-23
        ->with('opening',$TotalPawn)
        ->with('Purchases',$TotalRedeem)
        ->with('OpeningInvoice', $OpeningHirePurchaseSum)
        ->with('customerdetails',$Pawningpayemt)
        ->with('QtyIn',$QtyIn)
        ->with('QtyOut',$QtyOut)
        ->with('PurchasesSum',$Redeempayment)
        ->with('salessum',$InvoiceSum );
       

    }
}
