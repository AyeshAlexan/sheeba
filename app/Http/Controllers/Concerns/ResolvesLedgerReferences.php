<?php

namespace App\Http\Controllers\Concerns;

use App\Models\TPaymentVoucher;
use App\Models\TGentralReceipt;
use App\Models\TwithoutVatSalesSum;
use App\Models\TInvoiceSum;
use App\Models\Customer;
use App\Models\Suppliers;
use App\Models\DailyTransaction;

/**
 * Shared by any report that lists raw t_account_trans rows and needs to
 * describe them meaningfully instead of showing the internal trance_type
 * code — used by the Cash & Cheque Transaction report and the unified
 * Daily Transactions / Cash Book feed, so both describe the same
 * underlying transaction the exact same way.
 */
trait ResolvesLedgerReferences
{
    /**
     * Sales/payment types that already have a real invoice or payment
     * number behind them.
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

        // Daily Transactions / Cash Book entries.
        if ($type === 'DAILY_TXN') {
            $label = $row->Description ?: ('Transaction ' . $ref);
            return [$label, route('daily.transactions', ['txn' => $ref])];
        }

        // Purchases (goods received) — no->purchase invoice number.
        if ($type === 'GRN') {
            return ['Purchase #' . $row->no, route('purchasing.report', ['purchase_no' => $row->no])];
        }

        // Supplier payments — trance_no is the real payment number here,
        // but the purchasing report is filtered by purchase number (no).
        if ($type === 'SUP_PAY') {
            return ['Supplier Payment #' . $row->trance_no, route('purchasing.report', ['purchase_no' => $row->no])];
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

        if ($row->trance_type === 'DAILY_TXN') {
            $source = DailyTransaction::where('ref_no', $ref)->first();
            if (!$source) {
                return null;
            }
            return trim(($source->dr_label ?: '?') . ' (DR) — ' . ($source->cr_label ?: '?') . ' (CR)');
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

        // Purchases and supplier payments — the supplier code is stored
        // directly on the row, no need to go via the purchase record.
        if (in_array($row->trance_type, ['GRN', 'SUP_PAY'])) {
            $side = ((float) $row->dr_amount > 0) ? 'DR' : 'CR';

            $supplierName = Suppliers::where('Code', $row->supplier)->value('Name')
                ?: $row->supplier;

            return $supplierName ? "{$supplierName} ({$side})" : null;
        }

        return null;
    }
}
