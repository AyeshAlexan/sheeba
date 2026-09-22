<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TAccountTrans;
use App\Models\TSupPurchaseTrance;
use App\Http\Controllers\Concerns\ResolvesLedgerReferences;

class DailyTransactionController extends Controller
{
    use ResolvesLedgerReferences;

    /**
     * Daily Transactions — a read-only, automatic log of every financial
     * transaction across all accounts (sales, payments, receipts,
     * vouchers, purchases, supplier payments), defaulting to today. For
     * just Cash/Cheque In Hand movements, see the Cash Book report.
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        if (!$fromDate || !$toDate) {
            $fromDate = now()->format('Y-m-d');
            $toDate   = now()->format('Y-m-d');
        }

        $branchCode = auth()->user()->BC;

        $rows = TAccountTrans::whereBetween('Ddate', [$fromDate, $toDate])
            ->where('BC', $branchCode)
            ->orderBy('Ddate', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Purchases (GRN) and Supplier Payments (SUP_PAY) post to a
        // completely separate supplier ledger table, not t_account_trans —
        // pull those in too and normalize the field names to match so the
        // view can treat every row the same way.
        $supplierRows = TSupPurchaseTrance::whereBetween('dDate', [$fromDate, $toDate])
            ->where('bc', $branchCode)
            ->get();

        foreach ($supplierRows as $row) {
            $row->Ddate       = $row->dDate;
            $row->Description = null;
        }

        $rows = $rows->concat($supplierRows)->sortByDesc('Ddate')->values();

        foreach ($rows as $row) {
            [$row->reference_label, $row->reference_url] = $this->resolveReference($row);
            $row->logic_summary = $this->resolveLogic($row);
        }

        $totalDr = $rows->sum('dr_amount');
        $totalCr = $rows->sum('cr_amount');

        return view('DailyTransactions', [
            'transactions' => $rows,
            'fromDate'     => $fromDate,
            'toDate'       => $toDate,
            'totalDr'      => $totalDr,
            'totalCr'      => $totalCr,
        ]);
    }
}
