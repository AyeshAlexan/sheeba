<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TwithoutVatSalesSum;
use App\Models\TPurchasesSum;
use App\Models\TOpeningSum;
use App\Models\TItemMovement;
use App\Models\TWithoutVatSalesDetails;
use App\Models\TCustomerPayment;
use App\Models\TSupplierPayment;
use App\Models\TSalesReturnSum;
use App\Models\TSalesReturnDetails;
use App\Models\TPurchasesDetails;
use App\Models\TPurchasesReturnSum;
use App\Models\TPurchasesReturnDetails;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ══════════════════════════════════════════════════════════════
    //  MAIN PAGE
    // ══════════════════════════════════════════════════════════════

    public function index()
    {
        // ── Summary counts & sums ───────────────────────────────────
        $totalPawnCount      = TOpeningSum::count();
        $totalPurchasesCount = TPurchasesSum::count();
        $totalInvoiceCount   = TwithoutVatSalesSum::count();

        $pawningPaymentSum   = TOpeningSum::sum('Amount');
        $purchasesPaymentSum = TPurchasesSum::sum('Net_Amount');
        $invoiceSumTotal     = TwithoutVatSalesSum::sum('Net_Amount');

        $qtyInTotal  = TItemMovement::sum('qun_in');
        $qtyOutTotal = TItemMovement::sum('qun_out');

        // ── Customers ────────────────────────────────────────────────
        $totalCustomerCount = Customer::count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ── Payments (sent vs received) ───────────────────────────────
        [$paymentSentSum, $paymentSentPct] = $this->monthOverMonth(
            TSupplierPayment::class, 'Payment_Amount', 'Payment_date'
        );
        [$paymentReceivedSum, $paymentReceivedPct] = $this->monthOverMonth(
            TCustomerPayment::class, 'Payment_Amount', 'Payment_date'
        );

        // ── Month-over-month % for the top metric cards ───────────────
        [, $salesMoMPct]     = $this->monthOverMonth(TwithoutVatSalesSum::class, 'Net_Amount', 'Invoice_date');
        [, $purchasesMoMPct] = $this->monthOverMonth(TPurchasesSum::class, 'Net_Amount', 'Invoice_date');
        [, $customersMoMPct] = $this->monthOverMonthCount(Customer::class, 'created_at');

        $statCards = [
            ['label' => 'Total Sales',      'value' => $invoiceSumTotal,     'decimals' => 2, 'pct' => $salesMoMPct,     'icon' => 'fa-chart-line',            'color' => 'indigo'],
            ['label' => 'Total Purchases',  'value' => $purchasesPaymentSum, 'decimals' => 2, 'pct' => $purchasesMoMPct, 'icon' => 'fa-shopping-cart',         'color' => 'sky'],
            ['label' => 'Customers',        'value' => $totalCustomerCount,  'decimals' => 0, 'pct' => $customersMoMPct, 'icon' => 'fa-users',                 'color' => 'violet'],
            ['label' => 'Payment Sent',     'value' => $paymentSentSum,      'decimals' => 2, 'pct' => $paymentSentPct,     'icon' => 'fa-upload',   'color' => 'amber'],
            ['label' => 'Payment Received', 'value' => $paymentReceivedSum,  'decimals' => 2, 'pct' => $paymentReceivedPct, 'icon' => 'fa-download', 'color' => 'emerald'],
        ];

        // ── Stock history (qty moved this month vs last month) ────────
        [$salesItemsQty, $salesItemsPct]         = $this->monthOverMonth(TWithoutVatSalesDetails::class, 'QTY', 'Invoice_date');
        [$salesReturnQty, $salesReturnPct]       = $this->monthOverMonth(TSalesReturnDetails::class, 'QTY', 'Invoice_date');
        [$purchaseItemsQty, $purchaseItemsPct]   = $this->monthOverMonth(TPurchasesDetails::class, 'QTY', 'Invoice_date');
        [$purchaseReturnQty, $purchaseReturnPct] = $this->monthOverMonth(TPurchasesReturnDetails::class, 'QTY', 'Invoice_date');

        $stockHistory = [
            ['label' => 'Total Sales Items',     'value' => $salesItemsQty,     'pct' => $salesItemsPct],
            ['label' => 'Sales Return Items',    'value' => $salesReturnQty,    'pct' => $salesReturnPct],
            ['label' => 'Total Purchase Items',  'value' => $purchaseItemsQty,  'pct' => $purchaseItemsPct],
            ['label' => 'Purchase Return Items', 'value' => $purchaseReturnQty, 'pct' => $purchaseReturnPct],
        ];

        // ── Stock details (QTY = qun_in − qun_out per item) ─────────
        $stockDetails = Item::select(
            'items.Item_code',
            'items.Bar_code',
            'items.category',
            'items.Item_description',
            'items.purchasePrice',
            'items.saleprice',
            'items.ReorderLevel',
            'items.RecorderQuantitiy',
            DB::raw('COALESCE(SUM(t_item_movements.qun_in),  0) AS total_qun_in'),
            DB::raw('COALESCE(SUM(t_item_movements.qun_out), 0) AS total_qun_out'),
            DB::raw('COALESCE(SUM(t_item_movements.qun_in),  0)
                   - COALESCE(SUM(t_item_movements.qun_out), 0) AS QTY'),
            DB::raw('MAX(t_item_movements.dDate) AS last_movement_date')
        )
        ->leftJoin('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
        ->groupBy(
            'items.Item_code', 'items.Bar_code', 'items.category',
            'items.Item_description', 'items.purchasePrice', 'items.saleprice',
            'items.ReorderLevel', 'items.RecorderQuantitiy'
        )
        ->get();

        // ── Default chart payloads (rendered server-side on first load) ─
        $salesChartData    = $this->buildSalesChartData('monthly');
        $invoiceChartData  = $this->buildInvoiceChartData('monthly');
        $topItemsData      = $this->buildTopItemsData('monthly');
        $paymentTrendData  = $this->buildPaymentTrendData('weekly');

        // ── Recent Sales Invoices (with derived payment/return status) ─
        $recentInvoicesRaw = TwithoutVatSalesSum::orderByDesc('Invoice_date')->limit(6)->get();

        $returnedInvoiceNos = TSalesReturnSum::whereIn('Invoice_no', $recentInvoicesRaw->pluck('Invoice_no'))
            ->pluck('Invoice_no')
            ->all();

        $recentInvoices = $recentInvoicesRaw->map(function ($inv) use ($returnedInvoiceNos) {
            $balance = (float) $inv->Balance;
            if (in_array($inv->Invoice_no, $returnedInvoiceNos)) {
                $status = 'Returned';
            } elseif ($balance <= 0) {
                $status = 'Paid';
            } elseif (Carbon::parse($inv->Invoice_date)->lt(now()->subDays(30))) {
                $status = 'Overdue';
            } else {
                $status = 'Unpaid';
            }
            $inv->computed_status = $status;
            return $inv;
        });

        $invoiceStatusCounts = [
            'Paid'     => $recentInvoices->where('computed_status', 'Paid')->count(),
            'Unpaid'   => $recentInvoices->where('computed_status', 'Unpaid')->count(),
            'Overdue'  => $recentInvoices->where('computed_status', 'Overdue')->count(),
            'Returned' => $recentInvoices->where('computed_status', 'Returned')->count(),
        ];

        // ── Recent Customer Payments (with payment-method breakdown) ──
        $recentPayments = TCustomerPayment::orderByDesc('Payment_date')
            ->limit(6)
            ->get()
            ->map(function ($p) {
                $methods = [
                    'Cash'  => (float) ($p->cash_payment ?? 0),
                    'Card'  => (float) ($p->card_payment ?? 0),
                    'Cheque'=> (float) ($p->cheque_payment ?? 0),
                    'Bank'  => (float) ($p->bank_transfer ?? 0),
                ];
                arsort($methods);
                $p->primary_method = array_key_first($methods) ?: 'Cash';
                return $p;
            });

        $paymentMethodTotals = [
            'Cash'   => (float) $recentPayments->sum('cash_payment'),
            'Card'   => (float) $recentPayments->sum('card_payment'),
            'Cheque' => (float) $recentPayments->sum('cheque_payment'),
            'Bank'   => (float) $recentPayments->sum('bank_transfer'),
        ];

        return view('home', [
            'opening'               => $totalPawnCount,
            'Purchases'             => $totalPurchasesCount,
            'Invoice'               => $totalInvoiceCount,
            'customerCount'         => $totalCustomerCount,
            'newCustomersThisMonth' => $newCustomersThisMonth,
            'QtyIn'                 => $qtyInTotal,
            'QtyOut'                => $qtyOutTotal,
            'PurchasesSum'          => $purchasesPaymentSum,
            'itemDetails'           => $stockDetails,
            'salessum'              => $invoiceSumTotal,
            'salesChartData'        => $salesChartData,
            'invoiceChartData'      => $invoiceChartData,
            'topItemsData'          => $topItemsData,
            'recentInvoices'        => $recentInvoices,
            'invoiceStatusCounts'   => $invoiceStatusCounts,
            'recentPayments'        => $recentPayments,
            'paymentMethodTotals'   => $paymentMethodTotals,
            'paymentSentSum'        => $paymentSentSum,
            'paymentSentPct'        => $paymentSentPct,
            'paymentReceivedSum'    => $paymentReceivedSum,
            'paymentReceivedPct'    => $paymentReceivedPct,
            'stockHistory'          => $stockHistory,
            'paymentTrendData'      => $paymentTrendData,
            'statCards'             => $statCards,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  AJAX ENDPOINTS
    // ══════════════════════════════════════════════════════════════

    public function getSalesChartData(Request $request)
    {
        return response()->json(
            $this->buildSalesChartData($request->get('period', 'monthly'))
        );
    }

    public function getInvoiceChartData(Request $request)
    {
        return response()->json(
            $this->buildInvoiceChartData($request->get('period', 'monthly'))
        );
    }

    public function getTopItemsData(Request $request)
    {
        return response()->json(
            $this->buildTopItemsData($request->get('period', 'monthly'))
        );
    }

    public function getPaymentTrendData(Request $request)
    {
        return response()->json(
            $this->buildPaymentTrendData($request->get('period', 'weekly'))
        );
    }

    // ══════════════════════════════════════════════════════════════
    //  PRIVATE CHART BUILDERS
    // ══════════════════════════════════════════════════════════════

    private function buildSalesChartData(string $period): array
    {
        [$groupExpr, $labelExpr, $range] = $this->periodConfig($period, 'Invoice_date');

        $rows = TwithoutVatSalesSum::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Net_Amount) AS received')
        )
        ->where('Invoice_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->get();

        $purchases = TPurchasesSum::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Net_Amount) AS pending')
        )
        ->where('Invoice_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->pluck('pending', 'label');

        $salesReturns = TSalesReturnSum::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Net_Amount) AS amt')
        )
        ->where('Invoice_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->pluck('amt', 'label');

        $purchaseReturns = TPurchasesReturnSum::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Net_Amount) AS amt')
        )
        ->where('Invoice_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->pluck('amt', 'label');

        $categories = $rows->pluck('label');
        $received   = $rows->pluck('received')->map(fn($v) => round($v / 1000, 2));
        $pending    = $categories->map(fn($lbl) => round(($purchases[$lbl] ?? 0) / 1000, 2));
        $returns    = $categories->map(fn($lbl) => round((($salesReturns[$lbl] ?? 0) + ($purchaseReturns[$lbl] ?? 0)) / 1000, 2));

        return compact('categories', 'received', 'pending', 'returns');
    }

    private function buildInvoiceChartData(string $period): array
    {
        [, , $invoiceRange] = $this->periodConfig($period, 'Invoice_date');

        // Document-count mix for the period — all three are comparable "how many
        // transactions" counts, unlike a raw stock quantity which would dwarf them.
        $sales     = TwithoutVatSalesSum::where('Invoice_date', '>=', $invoiceRange)->count();
        $purchases = TPurchasesSum::where('Invoice_date',       '>=', $invoiceRange)->count();
        $returns   = TSalesReturnSum::where('Invoice_date',     '>=', $invoiceRange)->count()
                   + TPurchasesReturnSum::where('Invoice_date', '>=', $invoiceRange)->count();

        $total = max($sales + $purchases + $returns, 1);

        $salesPct     = round($sales     / $total * 100, 1);
        $purchasesPct = round($purchases / $total * 100, 1);
        $returnsPct   = round(max(100 - $salesPct - $purchasesPct, 0), 1);

        return [
            'labels'  => ['Sales', 'Purchases', 'Returns'],
            'series'  => [$salesPct, $purchasesPct, $returnsPct],
            'totals'  => [
                'sales'     => $sales,
                'purchases' => $purchases,
                'returns'   => $returns,
            ],
        ];
    }

    private function buildTopItemsData(string $period): array
    {
        [, , $range] = $this->periodConfig($period, 'Invoice_date');

        $rows = TWithoutVatSalesDetails::select(
            'Item_code',
            'Item_description',
            DB::raw('SUM(QTY) AS qty'),
            DB::raw('SUM(Net_value) AS revenue')
        )
        ->where('Invoice_date', '>=', $range)
        ->groupBy('Item_code', 'Item_description')
        ->orderByDesc(DB::raw('SUM(Net_value)'))
        ->limit(10)
        ->get();

        return [
            'categories' => $rows->map(fn($r) => $r->Item_description ?: $r->Item_code)->values(),
            'revenue'    => $rows->map(fn($r) => round($r->revenue, 2))->values(),
            'qty'        => $rows->map(fn($r) => (int) $r->qty)->values(),
        ];
    }

    private function buildPaymentTrendData(string $period): array
    {
        [$groupExpr, $labelExpr, $range] = $this->periodConfig($period, 'Payment_date');

        $sentMap = TSupplierPayment::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Payment_Amount) AS value')
        )
        ->where('Payment_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->pluck('value', 'label');

        $receivedMap = TCustomerPayment::select(
            DB::raw("$labelExpr AS label"),
            DB::raw('SUM(Payment_Amount) AS value')
        )
        ->where('Payment_date', '>=', $range)
        ->groupBy(DB::raw($groupExpr))
        ->orderBy(DB::raw($groupExpr))
        ->pluck('value', 'label');

        $categories = collect(array_unique(array_merge(
            $sentMap->keys()->toArray(),
            $receivedMap->keys()->toArray()
        )))->sort()->values();

        $toSeries = fn($map) => $categories->map(fn($lbl) => round($map[$lbl] ?? 0, 2))->values();

        return [
            'categories' => $categories,
            'sent'       => $toSeries($sentMap),
            'received'   => $toSeries($receivedMap),
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  MONTH-OVER-MONTH HELPER
    // ══════════════════════════════════════════════════════════════

    /**
     * Sums $amountCol for the current calendar month and returns
     * [currentMonthSum, pctChangeVsPreviousMonth].
     */
    private function monthOverMonth(string $modelClass, string $amountCol, string $dateCol): array
    {
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd   = now()->startOfMonth();

        $current = (float) $modelClass::where($dateCol, '>=', $thisMonthStart)->sum($amountCol);
        $previous = (float) $modelClass::where($dateCol, '>=', $lastMonthStart)
            ->where($dateCol, '<', $lastMonthEnd)
            ->sum($amountCol);

        $pct = $previous > 0
            ? round(($current - $previous) / $previous * 100, 1)
            : ($current > 0 ? 100.0 : 0.0);

        return [$current, $pct];
    }

    /**
     * Counts rows for the current calendar month and returns
     * [currentMonthCount, pctChangeVsPreviousMonth].
     */
    private function monthOverMonthCount(string $modelClass, string $dateCol): array
    {
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd   = now()->startOfMonth();

        $current  = $modelClass::where($dateCol, '>=', $thisMonthStart)->count();
        $previous = $modelClass::where($dateCol, '>=', $lastMonthStart)
            ->where($dateCol, '<', $lastMonthEnd)
            ->count();

        $pct = $previous > 0
            ? round(($current - $previous) / $previous * 100, 1)
            : ($current > 0 ? 100.0 : 0.0);

        return [$current, $pct];
    }

    // ══════════════════════════════════════════════════════════════
    //  PERIOD HELPER
    // ══════════════════════════════════════════════════════════════

    private function periodConfig(string $period, string $col = 'Invoice_date'): array
    {
        return match ($period) {
            'daily' => [
                "DATE($col)",
                "DATE(MIN($col))",
                Carbon::now()->subDays(14),
            ],
            'weekly' => [
                "YEARWEEK($col, 1)",
                "DATE_FORMAT(MIN($col), '%d %b')",
                Carbon::now()->subWeeks(12),
            ],
            'yearly' => [
                "YEAR($col)",
                "YEAR(MIN($col))",
                Carbon::now()->subYears(5),
            ],
            default => [
                "DATE_FORMAT($col, '%Y-%m')",
                "DATE_FORMAT(MIN($col), '%b %Y')",
                Carbon::now()->subMonths(12),
            ],
        };
    }
}