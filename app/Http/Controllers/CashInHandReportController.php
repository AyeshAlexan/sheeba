<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TAccountTrans;
use App\Models\DayEndBalance;
use App\Models\Company;
use App\Models\branchDel;
use Carbon\Carbon;

class CashInHandReportController extends Controller
{
    /**
     * Display Cash In Hand Report with Opening Balance
     */
    public function index(Request $request)
    {
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        if (!$fromDate || !$toDate) {
            $fromDate = now()->startOfMonth()->format('Y-m-d');
            $toDate   = now()->endOfMonth()->format('Y-m-d');
        }

        // ── Fetch transactions ──────────────────────────────────────────────
        $invoice = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->orderBy('Ddate')
            ->get();

        // ── Totals ──────────────────────────────────────────────────────────
        $sumDrAmount = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $sumCrAmount = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('cr_amount');


        $totalCrAmount = number_format($sumCrAmount, 2);

        // ── Opening Balance (closing balance of the previous closed day) ────
        $openingBalance    = DayEndBalance::getOpeningBalance($branch_code, $fromDate);

        $totalDrAmountopeningBalance = $sumDrAmount + $openingBalance;

        $totalDrAmount = number_format($totalDrAmountopeningBalance, 2);

        // ── Balance = CR - DR ───────────────────────────────────────────────
        $balance           = ($openingBalance + $sumDrAmount ) -  $sumCrAmount;
        $totalCrAmountSum = $openingBalance + $sumCrAmount;
        $totalBalance      = number_format($balance, 2);
        $openingBalanceFmt = number_format($openingBalance, 2);

        // ── Is today already closed? ────────────────────────────────────────
        $todayIsClosed = DayEndBalance::isDateClosed($branch_code, $toDate);

        // ── Last closed day record (shown in UI) ────────────────────────────
        $lastClosed = DayEndBalance::where('BC', $branch_code)
            ->orderBy('close_date', 'desc')
            ->first();

        return view('cashInHandReport', compact(
            'invoice',
            'fromDate',
            'toDate',
            'totalDrAmount',
            'totalCrAmount',
            'totalBalance',
            'openingBalanceFmt',
            'totalDrAmountopeningBalance',
            'todayIsClosed',
            'lastClosed',
            'totalCrAmountSum'
        ));
    }

    public function print(Request $request)
    {
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        if (!$fromDate || !$toDate) {
            $fromDate = now()->startOfMonth()->format('Y-m-d');
            $toDate   = now()->endOfMonth()->format('Y-m-d');
        }

        $invoice = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->orderBy('Ddate')
            ->get();

        $sumDrAmount = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $sumCrAmount = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        $totalCrAmount = number_format($sumCrAmount, 2);

        $openingBalance = DayEndBalance::getOpeningBalance($branch_code, $fromDate);
        $totalDrAmount  = number_format($sumDrAmount + $openingBalance, 2);

        $balance            = ($openingBalance + $sumDrAmount) - $sumCrAmount;
        $totalBalance       = number_format($balance, 2);
        $openingBalanceFmt  = number_format($openingBalance, 2);

        return view('reports.print.cash-in-hand', [
            'invoice' => $invoice,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalDrAmount' => $totalDrAmount,
            'totalCrAmount' => $totalCrAmount,
            'totalBalance' => $totalBalance,
            'openingBalanceFmt' => $openingBalanceFmt,
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * Day End Close – save closing balance to day_end_balances
     */
    public function dayEndClose(Request $request)
    {
        $request->validate([
            'close_date' => 'required|date',
        ]);

        $branch_code = auth()->user()->BC;
        $closeDate   = $request->input('close_date');

        // Prevent double-close
        if (DayEndBalance::isDateClosed($branch_code, $closeDate)) {
            return back()->with('error', "Day end for {$closeDate} has already been closed.");
        }

        // Calculate totals for the closing date
        $sumDr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $sumCr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        // Opening balance of this day = closing balance of previous day
        $openingBalance = DayEndBalance::getOpeningBalance($branch_code, $closeDate);

        // ── Balance = CR - DR ───────────────────────────────────────────────
        $closingBalance = $openingBalance + $sumDr - $sumCr;

        DayEndBalance::create([
            'BC'              => $branch_code,
            'close_date'      => $closeDate,
            'closing_balance' => $closingBalance,
            'total_dr'        => $sumDr,
            'total_cr'        => $sumCr,
            'closed_by'       => auth()->id(),
            'closed_at'       => now(),
        ]);

        return back()->with('success', "Day end closed successfully for {$closeDate}. Closing Balance: " . number_format($closingBalance, 2));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}