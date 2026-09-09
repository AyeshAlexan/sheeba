<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TwithoutVatSalesSum;
use App\Models\TPurchasesSum;
use App\Models\TOpeningSum;
use App\Models\TItemMovement;
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
        $salesChartData   = $this->buildSalesChartData('monthly');
        $invoiceChartData = $this->buildInvoiceChartData('monthly');
        $multiLineData    = $this->buildMultiLineData('weekly');

        return view('home', [
            'opening'          => $totalPawnCount,
            'Purchases'        => $totalPurchasesCount,
            'Invoice'          => $totalInvoiceCount,
            'customerdetails'  => $pawningPaymentSum,
            'QtyIn'            => $qtyInTotal,
            'QtyOut'           => $qtyOutTotal,
            'PurchasesSum'     => $purchasesPaymentSum,
            'itemDetails'      => $stockDetails,
            'salessum'         => $invoiceSumTotal,
            'salesChartData'   => $salesChartData,
            'invoiceChartData' => $invoiceChartData,
            'multiLineData'    => $multiLineData,
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

    public function getMultiLineData(Request $request)
    {
        return response()->json(
            $this->buildMultiLineData($request->get('period', 'weekly'))
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

        $categories = $rows->pluck('label');
        $received   = $rows->pluck('received')->map(fn($v) => round($v / 1000, 2));
        $pending    = $categories->map(fn($lbl) => round(($purchases[$lbl] ?? 0) / 1000, 2));

        return compact('categories', 'received', 'pending');
    }

    private function buildInvoiceChartData(string $period): array
    {
        [, , $invoiceRange]  = $this->periodConfig($period, 'Invoice_date');
        [, , $movementRange] = $this->periodConfig($period, 'dDate');

        $sales     = TwithoutVatSalesSum::where('Invoice_date', '>=', $invoiceRange)->count();
        $purchases = TPurchasesSum::where('Invoice_date',       '>=', $invoiceRange)->count();
        $stock     = (int) TItemMovement::where('dDate',        '>=', $movementRange)->sum('qun_in');

        $total = max($sales + $purchases + $stock, 1);

        $salesPct     = round($sales     / $total * 100, 1);
        $purchasesPct = round($purchases / $total * 100, 1);
        $stockPct     = round($stock     / $total * 100, 1);
        $otherPct     = round(max(100 - $salesPct - $purchasesPct - $stockPct, 0), 1);

        return [
            'labels'  => ['Sales', 'Purchases', 'Stock', 'Other'],
            'series'  => [$salesPct, $purchasesPct, $stockPct, $otherPct],
            'totals'  => [
                'sales'     => $sales,
                'purchases' => $purchases,
                'stock'     => $stock,
            ],
        ];
    }

    private function buildMultiLineData(string $period): array
    {
        [$groupInv, $labelInv, $rangeInv] = $this->periodConfig($period, 'Invoice_date');
        [$groupMov, $labelMov, $rangeMov] = $this->periodConfig($period, 'dDate');

        $salesMap = TwithoutVatSalesSum::select(
            DB::raw("$labelInv AS label"),
            DB::raw('SUM(Net_Amount) AS value')
        )
        ->where('Invoice_date', '>=', $rangeInv)
        ->groupBy(DB::raw($groupInv))
        ->orderBy(DB::raw($groupInv))
        ->pluck('value', 'label');

        $purchasesMap = TPurchasesSum::select(
            DB::raw("$labelInv AS label"),
            DB::raw('SUM(Net_Amount) AS value')
        )
        ->where('Invoice_date', '>=', $rangeInv)
        ->groupBy(DB::raw($groupInv))
        ->orderBy(DB::raw($groupInv))
        ->pluck('value', 'label');

        $stockMap = TItemMovement::select(
            DB::raw("$labelMov AS label"),
            DB::raw('SUM(qun_in) AS value')
        )
        ->where('dDate', '>=', $rangeMov)
        ->groupBy(DB::raw($groupMov))
        ->orderBy(DB::raw($groupMov))
        ->pluck('value', 'label');

        $categories = collect(array_unique(array_merge(
            $salesMap->keys()->toArray(),
            $purchasesMap->keys()->toArray(),
            $stockMap->keys()->toArray()
        )))->sort()->values();

        $toSeries = fn($map) => $categories
            ->map(fn($lbl) => round($map[$lbl] ?? 0))
            ->values();

        $salesArr     = $toSeries($salesMap);
        $purchasesArr = $toSeries($purchasesMap);
        $stockArr     = $toSeries($stockMap);

        $pct = function ($arr) {
            if ($arr->count() < 2) return 0;
            $prev = $arr->slice(-2, 1)->first();
            $last = $arr->last();
            return round(($last - $prev) / max(abs($prev), 1) * 100, 1);
        };

        return [
            'categories' => $categories,
            'series'     => [
                ['name' => 'Sales',     'data' => $salesArr,     'color' => '#1677FF'],
                ['name' => 'Purchases', 'data' => $purchasesArr, 'color' => '#16A34A'],
                ['name' => 'Stock In',  'data' => $stockArr,     'color' => '#F59E0B'],
            ],
            'summary' => [
                ['label' => 'Sales',     'value' => $salesArr->sum(),     'pct' => $pct($salesArr)],
                ['label' => 'Purchases', 'value' => $purchasesArr->sum(), 'pct' => $pct($purchasesArr)],
                ['label' => 'Stock In',  'value' => $stockArr->sum(),     'pct' => $pct($stockArr)],
            ],
        ];
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