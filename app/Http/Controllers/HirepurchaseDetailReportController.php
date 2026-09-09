<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\THirePurchaseDetails;
use App\Models\THirePurchaseSum;

class HirepurchaseDetailReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        $query = THirePurchaseSum::select('t_hire_purchase_sums.*',
         't_hire_purchase_details.invoice_no as invoice_no',
         't_hire_purchase_details.Item_s_code as Item_s_code',
        't_hire_purchase_details.item_description as item_description'
         )
            ->join('t_hire_purchase_details', 't_hire_purchase_sums.invoice_no', '=', 't_hire_purchase_details.invoice_no')
            ->where('t_hire_purchase_sums.is_cash_converted',0)
            ->whereBetween('t_hire_purchase_sums.invoice_date', [$fromDate, $toDate])
            ->get();

        $totalDocumentCharge = $query->sum('document_charge');
        $totalDownpayment = $query->sum('down_payment');
        $totalTransport = $query->sum('transport');
        $totalInstalmentAmount = $query->sum('instalment_amount');
        $totalNoOFInstalment = $query->sum('no_of_instalment');
        $totalInstalment = $query->sum('t_hire_purchase_details.instalment');
        $totalGross = $query->sum('gross_amount');
        $totalDiscount = $query->sum('discount');
        $totalnet_amount = $query->sum('net_amount');
        $totalcash_payment = $query->sum('cash_payment');
        $final_due_amount =  $query->sum('due_amount');

        $TotalPawn = THirePurchaseSum::count();

        return view('reports.HirepurchaseDetailReport')
            ->with("invoice", $query)
            ->with("recipts", $query)
            ->with("document_charge", number_format($totalDocumentCharge, 2))
            ->with("down_payment", number_format($totalDownpayment, 2))
            ->with("transport", number_format($totalTransport, 2))
            ->with("instalment_amount", number_format($totalInstalmentAmount, 2))
            ->with("no_of_instalment", number_format($totalNoOFInstalment, 2))
            ->with("instalment", number_format($totalInstalment, 2))
            ->with("gross_amount", number_format($totalGross, 2))
            ->with("discount", number_format($totalDiscount, 2))
            ->with("net_amount", number_format($totalnet_amount, 2))
            ->with("final_due_amount", number_format($final_due_amount, 2))
            ->with("cash_payment", number_format($totalcash_payment, 2));
    }
}
