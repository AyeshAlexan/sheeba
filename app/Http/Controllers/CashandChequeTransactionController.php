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

class CashandChequeTransactionController extends Controller
{
    /**
     * Sales/payment types that already have a real invoice or payment
     * number behind them — same mapping used for the Customer Account
     * Report (Item 1), reused here so both reports describe the same
     * underlying transaction the same way.
     */
    private const SALES_TYPES = [
        'SALES'         => ['label' => 'Invoice', 'route' => 'print.invoice'],
        'SALES_OUT_VAT' => ['label' => 'Invoice', 'route' => 'sales.invoice.print'],
    ];

    private const PAYMENT_TYPES = [
        'TCP_CUS_PAY' => 'Payment',
        'CR_CUS_PAY'  => 'Receipt',
    ];

    /**
     * Work out a meaningful, and where possible clickable, description for
     * a cash/cheque ledger row instead of the raw "TYPE-Description" string.
     *
     * @return array{0: string, 1: ?string}
     */
    private function resolveReference($row): array
    {
        $type = $row->trance_type;
        $ref  = $row->trance_no ?: $row->no;
        $branchCode = auth()->user()->BC;

        if (isset(self::SALES_TYPES[$type])) {
            $meta  = self::SALES_TYPES[$type];
            $label = $meta['label'] . ' #' . $ref;
            $url   = route($meta['route'], ['invoice_no' => $ref, 'branch_code' => $branchCode]);
            return [$label, $url];
        }

        if (isset(self::PAYMENT_TYPES[$type])) {
            // The stored reference here is the sales/customer number, not a
            // real payment number — one sales reference can have several
            // payments against it — so we link to that group of payments
            // rather than claiming to point at one exact record.
            $label = self::PAYMENT_TYPES[$type] . ' (Ref ' . $ref . ')';
            return [$label, route('customer_payment_report', ['payment' => $ref])];
        }

        // General Receipt entries: the Description is already a meaningful,
        // user-typed note (e.g. "Maghi chq 814318") — just drop the noisy
        // "RECEIPT-" prefix and link back to the receipts list, filtered
        // down to this one record via a query param the list page reads.
        if ($type === 'RECEIPT') {
            $label = $row->Description ?: ('Receipt #' . $ref);
            return [$label, route('gentralreceipt', ['receipt' => $ref])];
        }

        // Payment Voucher entries — same idea, different source page.
        if ($type === 'VOUCHER') {
            $label = $row->Description ?: ('Voucher #' . $ref);
            return [$label, route('PaymentVoucher', ['voucher' => $ref])];
        }

        // Unknown/legacy type: fall back to whatever description exists
        // rather than the raw internal type code.
        return [$row->Description ?: $type, null];
    }

    /**
     * A plain-language summary of which accounts this movement was
     * between, e.g. "Phone Bill (DR) — Cash in Hand (CR)". Built from the
     * DR/CR account labels already stored on the source Voucher/Receipt
     * record — which is also where a customer's name naturally shows up,
     * since whoever entered it typed the customer's name into that same
     * account field. No separate customer-matching logic needed.
     */
    private function resolveLogic($row): ?string
    {
        $ref = $row->trance_no ?: $row->no;

        if ($row->trance_type === 'VOUCHER') {
            $source = TPaymentVoucher::where('invoice_no', $ref)->first();
            if (!$source || (!$source->dramount && !$source->cramount)) {
                return null;
            }
            return trim(($source->dramount ?: '?') . ' (DR) — ' . ($source->cramount ?: '?') . ' (CR)');
        }

        if ($row->trance_type === 'RECEIPT') {
            $source = TGentralReceipt::where('invoice_no', $ref)->first();
            if (!$source || (!$source->dramount && !$source->cramount)) {
                return null;
            }
            return trim(($source->dramount ?: '?') . ' (DR) — ' . ($source->cramount ?: '?') . ' (CR)');
        }

        // Sales invoices don't have a manual description — instead, show
        // which customer this leg belongs to and which side of the entry
        // this particular row is (each ledger row is only one side).
        if (in_array($row->trance_type, ['SALES', 'SALES_OUT_VAT'])) {
            $side = ((float) $row->dr_amount > 0) ? 'DR' : 'CR';

            $sale = $row->trance_type === 'SALES_OUT_VAT'
                ? TwithoutVatSalesSum::where('Invoice_no', $ref)->first()
                : TInvoiceSum::where('Invoice_no', $ref)->first();

            if (!$sale) {
                return null;
            }

            // Fall back through: name on the invoice, name in the Customer
            // master, then finally just the customer code — so this only
            // ever goes blank if there's no customer reference at all.
            $customerName = $sale->Customer_Name
                ?: Customer::where('Code', $sale->Customer_NIC)->value('Name')
                ?: $sale->Customer_NIC;

            return $customerName ? "{$customerName} ({$side})" : null;
        }

        return null;
    }

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
