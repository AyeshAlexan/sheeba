<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TAccountTrans;
use App\Models\TSupPurchaseTrance;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\DayEndBalance;
use App\Models\Customer;
use App\Http\Controllers\Concerns\ResolvesLedgerReferences;

class DailyTransactionController extends Controller
{
    use ResolvesLedgerReferences;

    /**
     * Daily Transactions — a read-only, automatic log of every financial
     * transaction across all accounts (sales, payments, receipts,
     * vouchers, purchases, supplier payments) plus banking actions
     * (cheque issued/deposited/returned, day-end close), defaulting to
     * today. For just Cash/Cheque In Hand movements, see the Cash Book
     * report.
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

        // This is the real general ledger — every row here is one half of a
        // balanced DR/CR pair (same trance_no), so its own DR and CR totals
        // should match. The one known exception is a one-time opening-balance
        // seed (CUS_OPEN_BC / BANK_OPEN), which is intentionally one-sided —
        // there's no second account to balance an opening value against.
        foreach ($rows as $row) {
            $row->ledger_source = 'General Ledger';
        }

        // Purchases (GRN) and Supplier Payments (SUP_PAY) post to a
        // completely separate supplier ledger table, not t_account_trans —
        // pull those in too and normalize the field names to match so the
        // view can treat every row the same way. This is its own
        // self-contained ledger (tracks what's owed to each supplier) and
        // was never meant to balance against the general ledger's totals.
        $supplierRows = TSupPurchaseTrance::whereBetween('dDate', [$fromDate, $toDate])
            ->where('bc', $branchCode)
            ->get();

        foreach ($supplierRows as $row) {
            $row->Ddate        = $row->dDate;
            $row->Description  = null;
            $row->ledger_source = 'Supplier Ledger';
        }

        $rows = $rows->concat($supplierRows)->sortByDesc('Ddate')->values();

        foreach ($rows as $row) {
            [$row->reference_label, $row->reference_url] = $this->resolveReference($row);
            $row->logic_summary = $this->resolveLogic($row);
        }

        // Banking & Account Actions — cheque status changes and day-end
        // close don't post double-entry ledger rows of their own (the
        // actual money movement was already posted at payment-entry
        // time), so they're shown here for visibility only, with DR/CR
        // left at 0 to avoid double-counting the report's totals.
        $bankingRows = collect();

        $supCheques = TSupCheque::whereBetween('status_changed_at', ["{$fromDate} 00:00:00", "{$toDate} 23:59:59"])
            ->where('bc', $branchCode)
            ->whereIn('cheque_status', ['ISSUED', 'DEPOSITED', 'RETURNED'])
            ->get();

        foreach ($supCheques as $c) {
            $label = [
                'ISSUED'    => 'Cheque Issued',
                'DEPOSITED' => 'Cheque Deposited',
                'RETURNED'  => 'Cheque Returned',
            ][$c->cheque_status] ?? $c->cheque_status;

            $bankingRows->push((object) [
                'Ddate'           => \Carbon\Carbon::parse($c->status_changed_at)->format('Y-m-d'),
                'reference_label' => "{$label} #{$c->cheques_no}",
                'reference_url'   => null,
                'Description'     => "{$label} — Supplier {$c->supplier_name} — Rs. " . number_format((float) $c->amount, 2),
                'logic_summary'   => 'Banking & Account Action',
                'ledger_source'   => 'Banking Action',
                'dr_amount'       => 0,
                'cr_amount'       => 0,
            ]);
        }

        $cusCheques = TCusCheque::whereBetween('status_changed_at', ["{$fromDate} 00:00:00", "{$toDate} 23:59:59"])
            ->where('bc', $branchCode)
            ->whereIn('cheque_status', ['deposit', 'return'])
            ->get();

        foreach ($cusCheques as $c) {
            $label        = $c->cheque_status === 'deposit' ? 'Cheque Deposited' : 'Cheque Returned';
            $customerCode = $c->customer ?: $c->trans_no;
            $customerName = Customer::where('Code', $customerCode)->value('Name') ?: $customerCode;

            $bankingRows->push((object) [
                'Ddate'           => \Carbon\Carbon::parse($c->status_changed_at)->format('Y-m-d'),
                'reference_label' => "{$label} #{$c->cheques_no}",
                'reference_url'   => null,
                'Description'     => "{$label} — Customer {$customerName} — Rs. " . number_format((float) $c->amount, 2),
                'logic_summary'   => 'Banking & Account Action',
                'ledger_source'   => 'Banking Action',
                'dr_amount'       => 0,
                'cr_amount'       => 0,
            ]);
        }

        $dayEndRows = DayEndBalance::whereBetween('close_date', [$fromDate, $toDate])
            ->where('BC', $branchCode)
            ->get();

        foreach ($dayEndRows as $d) {
            $bankingRows->push((object) [
                'Ddate'           => $d->close_date,
                'reference_label' => 'Day End Close',
                'reference_url'   => null,
                'Description'     => 'Day End Closed — Cash: ' . number_format((float) $d->cash_closing_balance, 2)
                    . ' | Cheque: ' . number_format((float) $d->cheque_closing_balance, 2)
                    . ' | Total: ' . number_format((float) $d->closing_balance, 2),
                'logic_summary'   => 'Banking & Account Action',
                'ledger_source'   => 'Banking Action',
                'dr_amount'       => 0,
                'cr_amount'       => 0,
            ]);
        }

        // Shown as three separate sections, not one merged table — the
        // General Ledger and Supplier Ledger are two independent,
        // self-balancing systems, so interleaving their rows (even with a
        // badge) made it look like one combined total was expected, which
        // it never was (e.g. a purchase posts only to the Supplier Ledger,
        // never to the General Ledger, and vice versa).
        $glRows      = $rows->where('ledger_source', 'General Ledger')->sortByDesc('Ddate')->values();
        $subRows     = $supplierRows->sortByDesc('Ddate')->values();
        $bankingRows = $bankingRows->sortByDesc('Ddate')->values();

        $glTotalDr  = $glRows->sum('dr_amount');
        $glTotalCr  = $glRows->sum('cr_amount');
        $subTotalDr = $subRows->sum('dr_amount');
        $subTotalCr = $subRows->sum('cr_amount');

        return view('DailyTransactions', [
            'glRows'       => $glRows,
            'subRows'      => $subRows,
            'bankingRows'  => $bankingRows,
            'fromDate'     => $fromDate,
            'toDate'       => $toDate,
            'glTotalDr'    => $glTotalDr,
            'glTotalCr'    => $glTotalCr,
            'subTotalDr'   => $subTotalDr,
            'subTotalCr'   => $subTotalCr,
        ]);
    }
}
