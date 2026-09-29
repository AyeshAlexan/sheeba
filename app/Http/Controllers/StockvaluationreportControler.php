<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockvaluationreportControler extends Controller
{
    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $itemCode = $request->input('item_code');

        $stockDetails = Item::select(
            'items.Item_code',
            'items.Item_description',
            'items.purchasePrice',
            'items.updated_at',
            DB::raw('SUM(t_item_movements.qun_in) AS total_qun_in'),
            DB::raw('SUM(t_item_movements.qun_out) AS total_qun_out')
        )
        ->leftJoin('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
        ->where('t_item_movements.bc', $branch_code)
        ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
        ->groupBy(
            'items.Item_code',
            'items.Item_description',
            'items.purchasePrice',
            'items.updated_at'
        )
        // No havingRaw — show ALL items (positive, zero, negative qty)
        ->orderBy('items.updated_at', 'desc')
        ->get();

        return $this->renderReport($stockDetails, $fromDate, $toDate, $itemCode);
    }

    public function FilterStoctValuation(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $itemCode = $request->input('item_code');

        $stockDetails = Item::select(
            'items.Item_code',
            'items.Item_description',
            'items.purchasePrice',
            'items.updated_at'
        )
        ->selectRaw('SUM(t_item_movements.qun_in) as total_qun_in')
        ->selectRaw('SUM(t_item_movements.qun_out) as total_qun_out')
        ->leftJoin('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
        ->where('t_item_movements.bc', $branch_code)
        ->whereBetween('t_item_movements.dDate', [$fromDate, $toDate])
        ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
        ->groupBy(
            'items.Item_code',
            'items.Item_description',
            'items.purchasePrice',
            'items.updated_at'
        )
        // No havingRaw — show ALL items (positive, zero, negative qty)
        ->orderBy('items.updated_at', 'desc')
        ->get();

        return $this->renderReport($stockDetails, $fromDate, $toDate, $itemCode);
    }

    private function renderReport($stockDetails, $fromDate, $toDate, $itemCode = null)
    {
        $quain   = $stockDetails->sum('total_qun_in');
        $quaout  = $stockDetails->sum('total_qun_out');
        $balance = $quain - $quaout;

        $sumPurchase = $stockDetails->sum('purchasePrice');

        $grandTotal = $stockDetails->sum(function ($stock) {
            $qty = $stock->total_qun_in - $stock->total_qun_out;
            return $qty * $stock->purchasePrice;
        });

        return view('reports.Stock_valuation_report', compact(
            'fromDate',
            'toDate',
            'itemCode',
            'stockDetails',
            'quain',
            'quaout',
            'sumPurchase',
            'balance',
            'grandTotal'
        ));
    }
}