<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\TItemMovement;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index(Request $request){

        $branch_code = auth()->user()->BC;
        $itemCode = $request->input('item_code');
        $category = $request->input('category');

        $stock = Item::select(
            'items.Item_code',
            'items.Bar_code',
            'items.category',
            'items.Item_description',
            'items.purchasePrice',
            'items.saleprice',
            DB::raw('SUM(t_item_movements.qun_in) AS total_qun_in'),
            DB::raw('SUM(t_item_movements.qun_out) AS total_qun_out'),
            DB::raw('SUM(t_item_movements.Free_Issues) AS total_free_issues'),
            DB::raw('SUM(t_item_movements.qun_in) - SUM(t_item_movements.qun_out) AS QTY'),
            DB::raw('MAX(t_item_movements.dDate) AS last_movement_date')
            )
         ->leftJoin('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
         ->where('t_item_movements.bc', $branch_code)
         ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
         ->when($category, fn ($q) => $q->where('items.category', $category))
         ->groupBy(
                'items.Item_code',
                'items.Bar_code',
                'items.category',
                'items.Item_description',
                'items.purchasePrice',
                'items.saleprice'
            )
        ->get();

        $quain = $stock->sum('total_qun_in');
        $quaout = $stock->sum('total_qun_out');
        $free_issues = $stock->sum('total_free_issues');
        $balance = $quain - $quaout - $free_issues;

        $fromDate = "";
        $toDate = "";

        return view('reports.stockReport')
            ->with("stockDetails", $stock)
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("itemCode", $itemCode)
            ->with("category", $category)
            ->with("quain", $quain)
            ->with("quaout", $quaout)
            ->with("balance", $balance);
    }

    public function filter(Request $request){
        $branch_code = auth()->user()->BC;

        $request->validate([
            'from_date' => 'required',
            'to_date'   => 'required',
        ]);

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;
        $itemCode = $request->input('item_code');
        $category = $request->input('category');

        $stock = Item::select(
            'items.Item_code',
            'items.Bar_code',
            'items.category',
            'items.Item_description',
            'items.purchasePrice',
            'items.saleprice'
            )
            ->selectRaw('SUM(t_item_movements.qun_in) as total_qun_in')
            ->selectRaw('SUM(t_item_movements.qun_out) as total_qun_out')
            ->selectRaw('SUM(t_item_movements.Free_Issues) as total_free_issues')
            ->selectRaw('MAX(t_item_movements.dDate) as last_movement_date')
            ->selectRaw('SUM(t_item_movements.qun_in) - SUM(t_item_movements.qun_out) AS QTY')
            ->leftJoin('t_item_movements', 'items.Item_code', '=', 't_item_movements.item_code')
            ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
            ->when($category, fn ($q) => $q->where('items.category', $category))
            ->groupBy(
                'items.Item_code',
                'items.Bar_code',
                'items.category',
                'items.Item_description',
                'items.purchasePrice',
                'items.saleprice'
            )
            ->whereBetween('t_item_movements.dDate', [$fromDate, $toDate])
            ->where('t_item_movements.bc', $branch_code)
            ->get();

        $quain       = $stock->sum('total_qun_in');
        $quaout      = $stock->sum('total_qun_out');
        $free_issues = $stock->sum('total_free_issues');
        $balance     = $quain - $quaout - $free_issues;

        return view('reports.stockReport')
            ->with("stockDetails", $stock)
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("itemCode", $itemCode)
            ->with("category", $category)
            ->with("quain", $quain)
            ->with("quaout", $quaout)
            ->with("balance", $balance);
    }
}