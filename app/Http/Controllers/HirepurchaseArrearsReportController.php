<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\THirePurchaseSum;
use App\Models\TInstalment;
use App\Models\MSchema;
use Carbon\Carbon;



class HirepurchaseArrearsReportController extends Controller
{
    public function index(Request $request){

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $scheme = $request->input('scheme');
        $customer = $request->input('customer');
        $branch_code = auth()->user()->BC;
        $schemaData = MSchema::all();

        $todayDate = Carbon::now()->format('Y-m-d');
        $invoice = TInstalment::when($fromDate && $toDate, fn ($q) => $q->whereBetween('instalment_date', [$fromDate, $toDate]))
                    ->whereRaw('amount_pay < instalment_amount')
                    ->where('bc', $branch_code)
                    ->when($scheme, fn ($q) => $q->where('schema_type', $scheme))
                    ->when($customer, fn ($q) => $q->where('customer_code', $customer))
                    ->get();

        $query = $invoice;

        // $sumGrossAmount = $query->sum('document_charge');
        // $totalGrossAmount = number_format($sumGrossAmount,2);

        // $sumUnit =  $query->sum('down_payment');
        // $totalUnit = number_format($sumUnit,2);

        // $sumDiscount =  $query->sum('transport');
        // $totalDiscount = number_format($sumDiscount,2);

        // $sumNetAmount =  $query->sum('instalment_amount');
        // $totalNetAmount = number_format($sumNetAmount,2);

        // $sumCashPay =  $query->sum('no_of_instalment');
        // $totalCashPay = number_format($sumCashPay,2);

        // $sumCredite =  $query->sum('instalment');
        // $totalCredite = number_format($sumCredite,2);

        // $sumgross_amount =  $query->sum('gross_amount');
        // $totalCheque = number_format($sumgross_amount,2);

        // $sumdiscount =  $query->sum('discount');
        // $totaldiscount = number_format($sumdiscount,2);

        // $sumnet_amount =  $query->sum('net_amount');
        // $totalnet_amount = number_format($sumnet_amount,2);

        // $cash_payment =  $query->sum('cash_payment');
        // $totalcash_payment = number_format($cash_payment,2);

        // $TotalPawn = TInstalment::count();

        return view('reports.HirepurchaseArrearsReport')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("scheme", $scheme)
        ->with("customer", $customer)
        ->with("invoice", $invoice)
        ->with("schemaData", $schemaData)
        ->with("recipts", $query);

        // -> with("document_charge", $totalGrossAmount)
        // -> with("down_payment", $totalUnit)
        // -> with("transport", $totalDiscount)
        // -> with("instalment_amount", $totalNetAmount)
        // -> with("no_of_instalment", $totalCashPay)
        // -> with("instalment", $totalCredite)
        // -> with("gross_amount", $totalCheque)
        // -> with("discount", $totaldiscount)
        // -> with("net_amount", $totalnet_amount)
        // -> with("cash_payment", $totalcash_payment);

    }
}
