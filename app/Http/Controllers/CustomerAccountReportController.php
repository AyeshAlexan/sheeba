<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TCusSaleTrance;

class CustomerAccountReportController extends Controller
{
    /**
     * Transaction types whose reference number is a Bill/Invoice number,
     * with the route (if any) that can open that specific document.
     * Route params are filled in at render time with [invoice_no, bc].
     */
    private const SALES_TYPES = [
        'SALES'          => ['label' => 'Invoice',    'route' => 'print.invoice'],
        'SALES_OUT_VAT'  => ['label' => 'Invoice',    'route' => 'sales.invoice.print'],
        'SALES_QUA'      => ['label' => 'Quotation',  'route' => null],
        'SALES_RETURN'   => ['label' => 'Return',     'route' => 'sales.return.print'],
        'SALES_DISCOUNT' => ['label' => 'Discount',   'route' => null],
        'Transport Payment' => ['label' => 'Transport', 'route' => null],
        'CUS_OPEN_BC'    => ['label' => 'Opening Bal.', 'route' => null],
        'CHEQUE'         => ['label' => 'Cheque',     'route' => null],
    ];

    private const PAYMENT_TYPES = [
        'TCP_CUS_PAY' => 'Payment',
        'CR_CUS_PAY'  => 'Receipt',
    ];

    public function index(Request $request)
    {
        $fromDate     = $request->input('from_date');
        $toDate       = $request->input('to_date');
        $customerCode = $request->input('customer');
        $branchCode   = auth()->user()->BC;

        $query = TCusSaleTrance::where('bc', $branchCode);

        if ($fromDate && $toDate) {
            $query->whereBetween('dDate', [$fromDate, $toDate]);
        }

        if ($customerCode) {
            $query->where('customer', $customerCode);
        }

        $invoice = $query->orderBy('dDate')->orderBy('id')->get();

        // Running balance + meaningful reference/link per row
        $balance = 0;
        foreach ($invoice as $row) {
            $balance += (float) $row->cr_amount - (float) $row->dr_amount;
            $row->running_balance = $balance;

            [$row->reference_label, $row->reference_url] = $this->resolveReference($row, $branchCode);
        }

        $sumDrAmount   = $invoice->sum('dr_amount');
        $sumCrAmount   = $invoice->sum('cr_amount');
        $totalDrAmount = number_format($sumDrAmount, 2);
        $totalCrAmount = number_format($sumCrAmount, 2);
        $totalBalance  = number_format($sumCrAmount - $sumDrAmount, 2);

        return view('user.reports.customer_account_report')
            ->with('invoice', $invoice)
            ->with('totalDrAmount', $totalDrAmount)
            ->with('totalCrAmount', $totalCrAmount)
            ->with('totalBalance', $totalBalance)
            ->with('fromDate', $fromDate)
            ->with('toDate', $toDate);
    }

    /**
     * Work out what to show in the "Transaction" column for a ledger row:
     * a meaningful business reference (invoice/payment number) instead of
     * the raw internal trance_type code, plus a link when a viewer page exists.
     *
     * @return array{0: string, 1: ?string}
     */
    private function resolveReference(TCusSaleTrance $row, $branchCode): array
    {
        $type = $row->trance_type;
        $ref  = $row->Display_Ref ?: $row->trance_no ?: $row->no;

        if (isset(self::SALES_TYPES[$type])) {
            $meta  = self::SALES_TYPES[$type];
            $label = $meta['label'] . ' #' . $ref;

            $url = null;
            if ($meta['route']) {
                $url = $meta['route'] === 'sales.return.print'
                    ? route($meta['route'], ['invoice_no' => $ref])
                    : route($meta['route'], ['invoice_no' => $ref, 'branch_code' => $branchCode]);
            }

            return [$label, $url];
        }

        if (isset(self::PAYMENT_TYPES[$type])) {
            $label = self::PAYMENT_TYPES[$type] . ' #' . $ref;
            return [$label, null];
        }

        // Unknown/legacy type: fall back to whatever reference we have rather than the raw code
        return [$ref ?: $type, null];
    }
}
