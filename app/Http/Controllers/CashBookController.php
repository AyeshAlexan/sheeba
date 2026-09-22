<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TAccountTrans;
use App\Http\Controllers\Concerns\ResolvesLedgerReferences;

class CashBookController extends Controller
{
    use ResolvesLedgerReferences;

    /**
     * The Cash Book — the true meaning: a read-only log of movements
     * through the Cash In Hand (201-001) and Cheque In Hand (201-123)
     * accounts only. For every transaction across all accounts, see the
     * Daily Transactions report instead.
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        if (!$fromDate || !$toDate) {
            $fromDate = now()->startOfMonth()->format('Y-m-d');
            $toDate   = now()->endOfMonth()->format('Y-m-d');
        }

        $branchCode = auth()->user()->BC;

        $rows = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('BC', $branchCode)
            ->whereIn('AccCode', ['201-001', '201-123'])
            ->orderBy('Ddate', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($rows as $row) {
            [$row->reference_label, $row->reference_url] = $this->resolveReference($row);
            $row->logic_summary = $this->resolveLogic($row);
        }

        // Totals from the currently filtered/displayed rows, not a
        // separate unfiltered query.
        $totalDr = $rows->sum('dr_amount');
        $totalCr = $rows->sum('cr_amount');

        return view('CashBook', [
            'transactions' => $rows,
            'fromDate'     => $fromDate,
            'toDate'       => $toDate,
            'totalDr'      => $totalDr,
            'totalCr'      => $totalCr,
        ]);
    }
}
