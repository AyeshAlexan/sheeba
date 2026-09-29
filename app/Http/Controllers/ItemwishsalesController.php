<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TWithoutVatSalesDetails;
use Illuminate\Support\Facades\DB;



class ItemwishsalesController extends Controller

{
public function index(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $itemCode = $request->input('item_code');
    $branch_code = auth()->user()->BC;

    // Item-wise sales summary with profit
    $invoice = TWithoutVatSalesDetails::join('items', 't_without_vat_sales_details.Item_code', '=', 'items.Item_code')
        ->whereBetween('t_without_vat_sales_details.Invoice_date', [$fromDate, $toDate])
        ->where('t_without_vat_sales_details.BC', $branch_code)
        ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
        ->groupBy('items.Item_code', 'items.Item_description', 'items.purchasePrice', 't_without_vat_sales_details.BC')
        ->select(
            'items.Item_code',
            'items.Item_description',
            'items.purchasePrice',
            't_without_vat_sales_details.BC',
            DB::raw('SUM(t_without_vat_sales_details.QTY) AS Qty'),
            DB::raw('SUM(COALESCE(t_without_vat_sales_details.Free_Issues, 0)) AS Free_Issues'),
            DB::raw('AVG(t_without_vat_sales_details.Unit_price) AS Unit_price'),
            DB::raw('SUM(t_without_vat_sales_details.Discount) AS discount'), // <--- added
            DB::raw('SUM(t_without_vat_sales_details.QTY * t_without_vat_sales_details.Unit_price) AS total_Price'),
            DB::raw('SUM((t_without_vat_sales_details.QTY + COALESCE(t_without_vat_sales_details.Free_Issues, 0)) * items.purchasePrice) AS purchase_price'),
            DB::raw('SUM((t_without_vat_sales_details.QTY * t_without_vat_sales_details.Unit_price) - ((t_without_vat_sales_details.QTY + COALESCE(t_without_vat_sales_details.Free_Issues, 0)) * items.purchasePrice) - t_without_vat_sales_details.Discount) AS profit')

        )
        ->get();

    // Overall sales summary
    $query = TWithoutVatSalesDetails::whereBetween('Invoice_date', [$fromDate, $toDate])
        ->where('BC', $branch_code)
        ->when($itemCode, fn ($q) => $q->where('Item_code', 'like', "%{$itemCode}%"));

    $sumGrossAmount = $query->sum('QTY');
    $sumGrossAmountFree_Issues = $query->sum(DB::raw('COALESCE(Free_Issues, 0)'));
    $sumTotalAmount = $query->sum(DB::raw('QTY * Unit_price'));
    $sumDiscount = $query->sum('Discount');
    $sumNetAmount = $query->sum('Net_value');
    $totalPawn = $query->count();

    // Total profit with correct Free_Issues handling
    $totalProfit = TWithoutVatSalesDetails::join('items', 't_without_vat_sales_details.Item_code', '=', 'items.Item_code')
        ->whereBetween('t_without_vat_sales_details.Invoice_date', [$fromDate, $toDate])
        ->where('t_without_vat_sales_details.BC', $branch_code)
        ->when($itemCode, fn ($q) => $q->where('items.Item_code', 'like', "%{$itemCode}%"))
        ->selectRaw('
            SUM(
                (t_without_vat_sales_details.QTY * t_without_vat_sales_details.Unit_price)
                - ((t_without_vat_sales_details.QTY + COALESCE(t_without_vat_sales_details.Free_Issues, 0)) * items.purchasePrice)
            ) - SUM(t_without_vat_sales_details.Discount) AS total_profit
        ')
        ->value('total_profit');

    return view('reports.Item_wish_sales_report', [
        "fromDate" => $fromDate,
        "toDate" => $toDate,
        "itemCode" => $itemCode,
        "invoice" => $invoice,
        "recipts" => $query->get(),
        "totalGrossAmount" => number_format($sumGrossAmount, 2),
        "sumGrossAmountFree_Issues" => number_format($sumGrossAmountFree_Issues, 2),
        "totalUnit" => number_format($sumTotalAmount, 2),
        "totalDiscount" => number_format($sumDiscount, 2),
        "totalNetAmount" => number_format($sumNetAmount, 2),
        "totalPawn" => $totalPawn,
        "totalProfit" => number_format($totalProfit, 2)
    ]);
}





}