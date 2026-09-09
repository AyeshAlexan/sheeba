<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TItemMovement;
use Illuminate\Support\Facades\DB;

class ZerostockreportController extends Controller
{
    public function index(Request $request){
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $invoice = TItemMovement::whereBetween('dDate', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->where('qun_in',0)
                    ->get();
        // $invoice = DB::table('items')
        //             ->join('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')// joining the contacts table , where user_id and contact_user_id are same
        //             ->select('items.*', 't_item_movements.qun_in','t_item_movements.qun_out','t_item_movements.dDate')
        //             ->whereBetween('t_item_movements.dDate', [$fromDate, $toDate])
        //             ->where('t_item_movements.bc', $branch_code)
        //             ->get();

        $query =  TItemMovement::query();

        if ($fromDate && $toDate) {
            $query =  TItemMovement::whereBetween('dDate', [$fromDate, $toDate])
                    ->where('BC',$branch_code)
                    ->where('qun_in',0)
                    ->get();

        }


        return view('reports.ZerostockReport')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        -> with("invoice", $invoice)
        -> with("recipts", $query);
        // -> with("totalGrossAmount", $totalGrossAmount);
        // -> with("totalDiscount", $totalDiscount)
        // -> with("totalNetAmount", $totalNetAmount)
        // -> with("totalCashPay", $totalCashPay)
        // -> with("totalCredite", $totalCredite)
        // -> with("totalCheque", $totalCheque);

    }
}
