<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPurchasesSum;
use App\Models\TPurchasesDetails;
use App\Models\Suppliers;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class PurchasingReportController extends Controller
{
    /**
     * The one, consolidated Purchasing Report — replaces the three older,
     * date-only, unfiltered reports. Supports combinable filters (purchase
     * number, supplier, product, product code, date range) and computes
     * totals from the currently filtered result set, not a separate query.
     */
    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;

        $purchaseNo  = $request->input('purchase_no');
        $supplier    = $request->input('supplier');
        $product     = $request->input('product');
        $productCode = $request->input('product_code');
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');

        $query = TPurchasesSum::leftJoin('suppliers', 'suppliers.Code', '=', 't_purchases_sums.Customer_NIC')
            ->where('t_purchases_sums.BC', $branch_code)
            ->select(
                't_purchases_sums.Invoice_no',
                't_purchases_sums.Invoice_date',
                't_purchases_sums.Customer_NIC as supplier_code',
                DB::raw('COALESCE(t_purchases_sums.Customer_Name, suppliers.Name) as supplier_name'),
                't_purchases_sums.Gross_Amount',
                't_purchases_sums.Discount',
                't_purchases_sums.Net_Amount',
                't_purchases_sums.credit_payment',
                't_purchases_sums.paid_amount',
                DB::raw('(select coalesce(sum(d.QTY), 0) from t_purchases_details d where d.Invoice_no = t_purchases_sums.Invoice_no) as total_qty')
            );

        if ($purchaseNo) {
            $query->where('t_purchases_sums.Invoice_no', 'like', "%{$purchaseNo}%");
        }

        if ($supplier) {
            $query->where(function ($q) use ($supplier) {
                $q->where('t_purchases_sums.Customer_NIC', 'like', "%{$supplier}%")
                  ->orWhere('suppliers.Name', 'like', "%{$supplier}%");
            });
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('t_purchases_sums.Invoice_date', [$fromDate, $toDate]);
        }

        if ($product || $productCode) {
            $query->whereExists(function ($sub) use ($product, $productCode) {
                $sub->select(DB::raw(1))
                    ->from('t_purchases_details as d')
                    ->whereColumn('d.Invoice_no', 't_purchases_sums.Invoice_no');
                if ($product) {
                    $sub->where('d.Item_description', 'like', "%{$product}%");
                }
                if ($productCode) {
                    $sub->where('d.Item_code', 'like', "%{$productCode}%");
                }
            });
        }

        $purchases = $query->orderBy('t_purchases_sums.Invoice_date', 'desc')->get();

        // Payment status + outstanding, computed per row from the same
        // figures used across the app's other supplier-balance screens.
        $purchases = $purchases->map(function ($row) {
            $credit = (float) ($row->credit_payment ?? 0);
            $paid   = (float) ($row->paid_amount ?? 0);

            if ($credit <= 0) {
                $row->outstanding    = 0;
                $row->payment_status = 'Cash';
            } else {
                $row->outstanding = round($credit - $paid, 2);
                if ($row->outstanding <= 0) {
                    $row->payment_status = 'Paid';
                } elseif ($paid > 0) {
                    $row->payment_status = 'Partial';
                } else {
                    $row->payment_status = 'Unpaid';
                }
            }

            return $row;
        });

        // Totals from the filtered/displayed set itself — not a separate,
        // unfiltered query (the bug in the old reports).
        $totals = [
            'gross'       => $purchases->sum('Gross_Amount'),
            'discount'    => $purchases->sum('Discount'),
            'net'         => $purchases->sum('Net_Amount'),
            'qty'         => $purchases->sum('total_qty'),
            'outstanding' => $purchases->sum('outstanding'),
        ];

        $suppliers = Suppliers::orderBy('Name')->get(['Code', 'Name']);
        $items     = Item::orderBy('Item_description')->get(['Item_code', 'Item_description']);

        return view('reports.purchasing_report', compact(
            'purchases', 'totals', 'suppliers', 'items',
            'purchaseNo', 'supplier', 'product', 'productCode', 'fromDate', 'toDate'
        ));
    }

    /**
     * Line items for a single purchase — powers the detail modal.
     */
    public function detail(Request $request)
    {
        $invoiceNo   = $request->input('invoice_no');
        $branch_code = auth()->user()->BC;

        $header = TPurchasesSum::leftJoin('suppliers', 'suppliers.Code', '=', 't_purchases_sums.Customer_NIC')
            ->where('t_purchases_sums.Invoice_no', $invoiceNo)
            ->where('t_purchases_sums.BC', $branch_code)
            ->select(
                't_purchases_sums.*',
                DB::raw('COALESCE(t_purchases_sums.Customer_Name, suppliers.Name) as supplier_name')
            )
            ->first();

        $lines = TPurchasesDetails::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)
            ->get();

        return response()->json([
            'status' => 'success',
            'header' => $header,
            'lines'  => $lines,
        ]);
    }
}
