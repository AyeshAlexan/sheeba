<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TAccountTrans;
use App\Models\DayEndBalance;
use App\Models\TPaymentVoucher;
use App\Models\TGentralReceipt;
use App\Models\TwithoutVatSalesSum;
use App\Models\TInvoiceSum;
use App\Models\Customer;
use App\Models\DailyTransaction;
use App\Http\Controllers\Concerns\ResolvesLedgerReferences;

class CashandChequeTransactionController extends Controller
{
    use ResolvesLedgerReferences;

    /**
     * Display Cash In Hand Report
     * 201-001 = Cash In Hand
     * 201-123 = Cheque In Hand
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

        // ══════════════════════════════════════════
        // CASH  (201-001)
        // ══════════════════════════════════════════
        $cashInvoice = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->orderBy('Ddate')
            ->get();

        foreach ($cashInvoice as $row) {
            [$row->reference_label, $row->reference_url] = $this->resolveReference($row);
            $row->logic_summary = $this->resolveLogic($row);
        }

        $cashSumDr = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $cashSumCr = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        $cashOpeningBalance = DayEndBalance::getOpeningBalance($branch_code, $fromDate, '201-001');
        $cashTotalDr        = $cashOpeningBalance + $cashSumDr;
        $cashBalance        = ($cashOpeningBalance + $cashSumDr) - $cashSumCr;

        // ══════════════════════════════════════════
        // CHEQUE  (201-123)
        // ══════════════════════════════════════════
        $chequeInvoice = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-123')
            ->where('BC', $branch_code)
            ->orderBy('Ddate')
            ->get();

        foreach ($chequeInvoice as $row) {
            [$row->reference_label, $row->reference_url] = $this->resolveReference($row);
            $row->logic_summary = $this->resolveLogic($row);
        }

        $chequeSumDr = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-123')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $chequeSumCr = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('AccCode', '201-123')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        $chequeOpeningBalance = DayEndBalance::getOpeningBalance($branch_code, $fromDate, '201-123');
        $chequeTotalDr        = $chequeOpeningBalance + $chequeSumDr;
        $chequeBalance        = ($chequeOpeningBalance + $chequeSumDr) - $chequeSumCr;

        // ══════════════════════════════════════════
        // COMBINED
        // ══════════════════════════════════════════
        $combinedBalance = $cashBalance + $chequeBalance;

        // Format
        $cashOpeningBalanceFmt   = number_format($cashOpeningBalance,   2);
        $chequeOpeningBalanceFmt = number_format($chequeOpeningBalance, 2);
        $cashTotalDrFmt          = number_format($cashTotalDr,          2);
        $cashTotalCrFmt          = number_format($cashSumCr,            2);
        $cashBalanceFmt          = number_format($cashBalance,          2);
        $chequeTotalDrFmt        = number_format($chequeTotalDr,        2);
        $chequeTotalCrFmt        = number_format($chequeSumCr,          2);
        $chequeBalanceFmt        = number_format($chequeBalance,        2);
        $combinedBalanceFmt      = number_format($combinedBalance,      2);

        $todayIsClosed = DayEndBalance::isDateClosed($branch_code, $toDate);

        $lastClosed = DayEndBalance::where('BC', $branch_code)
            ->orderBy('close_date', 'desc')
            ->first();

        return view('cashandChequeTransaction', compact(
            'cashInvoice',
            'cashOpeningBalanceFmt',
            'cashTotalDrFmt',
            'cashTotalCrFmt',
            'cashBalanceFmt',
            'cashBalance',
            'chequeInvoice',
            'chequeOpeningBalanceFmt',
            'chequeTotalDrFmt',
            'chequeTotalCrFmt',
            'chequeBalanceFmt',
            'chequeBalance',
            'combinedBalance',
            'combinedBalanceFmt',
            'fromDate',
            'toDate',
            'todayIsClosed',
            'lastClosed'
        ));
    }

    /**
     * Day End Close
     * Saves BOTH cash_closing_balance (201-001) AND cheque_closing_balance (201-123)
     * into the day_end_balances table in ONE row.
     *
     * day_end_balances columns:
     *   closing_balance        → cash + cheque combined
     *   cash_closing_balance   → 201-001 cash only
     *   cheque_closing_balance → 201-123 cheque only
     *   total_dr               → combined DR
     *   total_cr               → combined CR
     */
    public function dayEndChequeClose(Request $request)
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

        // ── CASH (201-001) ──────────────────────────────────────────────
        $cashDr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $cashCr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-001')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        $cashOpening        = DayEndBalance::getOpeningBalance($branch_code, $closeDate, '201-001');
        $cashClosingBalance = $cashOpening + $cashDr - $cashCr;

        // ── CHEQUE (201-123) ────────────────────────────────────────────
        $chequeDr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-123')
            ->where('BC', $branch_code)
            ->sum('dr_amount');

        $chequeCr = TAccountTrans::whereDate('Ddate', $closeDate)
            ->where('AccCode', '201-123')
            ->where('BC', $branch_code)
            ->sum('cr_amount');

        $chequeOpening        = DayEndBalance::getOpeningBalance($branch_code, $closeDate, '201-123');
        $chequeClosingBalance = $chequeOpening + $chequeDr - $chequeCr;

        // ── Combined ────────────────────────────────────────────────────
        $combinedClosing = $cashClosingBalance + $chequeClosingBalance;

        // ── Save ONE row to day_end_balances ────────────────────────────
        DayEndBalance::create([
            'BC'                    => $branch_code,
            'close_date'            => $closeDate,
            'closing_balance'       => $combinedClosing,        // cash + cheque
            'cash_closing_balance'  => $cashClosingBalance,     // 201-001
            'cheque_closing_balance'=> $chequeClosingBalance,   // 201-123
            'total_dr'              => $cashDr + $chequeDr,
            'total_cr'              => $cashCr + $chequeCr,
            'closed_by'             => auth()->id(),
            'closed_at'             => now(),
        ]);

        return back()->with('success',
            "Day end closed for {$closeDate}. " .
            "Cash: "      . number_format($cashClosingBalance,   2) .
            " | Cheque: " . number_format($chequeClosingBalance, 2) .
            " | Total: "  . number_format($combinedClosing,      2)
        );
    }

    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
