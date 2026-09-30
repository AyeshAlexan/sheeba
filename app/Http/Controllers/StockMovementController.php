<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TItemMovement;

class StockMovementController extends Controller
{
    /**
     * Every type of label used across the app for a stock-affecting
     * transaction — Purchases (GRN), Sales, Stock Adjustment, Stock
     * Damage, Sales Return (both the current SALES_RETURN code path and
     * the older SPN one, which still has historical rows), Opening Stock,
     * Stock Transfer. Read-only: this page is purely for checking what
     * happened to an item's stock and why, in one place — entries are
     * still made from each feature's own page (Purchases, Sales, Stock
     * Damage, Sales Return, etc.).
     */
    private const TYPE_LABELS = [
        'GRN'               => 'Purchase',
        'PRN'               => 'Purchase Return',
        'SALES'             => 'Sales Invoice',
        'SALES_OUT_VAT'     => 'Sales Invoice',
        'SALES_QUA'         => 'Sales Quotation',
        'SALES_RETURN'      => 'Sales Return',
        'SPN'               => 'Sales Return',
        'HP_SALES'          => 'Hire Purchase Sale',
        'OPS'               => 'Opening Stock',
        'STOCK_TRANSFER'    => 'Stock Transfer',
        'STOCK_ADJUSTMENT'  => 'Stock Adjustment',
        'STOCK_DAMAGE'      => 'Stock Damage',
    ];

    public function index(Request $request)
    {
        $branchCode = auth()->user()->BC;

        $fromDate = $request->input('from_date') ?: now()->startOfMonth()->format('Y-m-d');
        $toDate   = $request->input('to_date') ?: now()->format('Y-m-d');
        $type     = $request->input('type');
        $itemCode = $request->input('item_code');

        $rows = TItemMovement::leftJoin('items', 'items.Item_code', '=', 't_item_movements.item_code')
            ->whereBetween('t_item_movements.dDate', [$fromDate, $toDate])
            ->where('t_item_movements.bc', $branchCode)
            ->when($type, fn ($q) => $q->where('t_item_movements.trans_code', $type))
            ->when($itemCode, fn ($q) => $q->where('t_item_movements.item_code', 'like', "%{$itemCode}%"))
            ->orderBy('t_item_movements.dDate', 'desc')
            ->orderBy('t_item_movements.id', 'desc')
            ->select(
                't_item_movements.id',
                't_item_movements.dDate',
                't_item_movements.trans_code',
                't_item_movements.trans_no',
                't_item_movements.item_code',
                'items.Item_description',
                't_item_movements.qun_in',
                't_item_movements.qun_out'
            )
            ->get();

        foreach ($rows as $row) {
            $row->type_label = self::TYPE_LABELS[$row->trans_code] ?? $row->trans_code;
        }

        $totalIn  = $rows->sum('qun_in');
        $totalOut = $rows->sum('qun_out');

        return view('stockMovements', [
            'rows'        => $rows,
            'fromDate'    => $fromDate,
            'toDate'      => $toDate,
            'type'        => $type,
            'itemCode'    => $itemCode,
            'totalIn'     => $totalIn,
            'totalOut'    => $totalOut,
            'typeOptions' => self::TYPE_LABELS,
        ]);
    }
}
