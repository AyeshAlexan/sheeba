<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\PackageItem;
use App\Models\Company;
use App\Models\branchDel;

class ItemdetailreportControler extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch items ordered by Category (asc) and then Item Code (asc)
        $invoice = Item::orderBy('Item_code', 'asc')
                       ->get();

        // 2. Since $query was doing the same thing as $invoice,
        // we can just reference the same collection to save memory.
        $query = $invoice;

        // 3. Totals
        $TotalPawn = $invoice->count();

        // 4. Fetch all package items for the sub-tables
        $setItems = PackageItem::all();

        return view('reports.Item_detail_report')
            ->with("invoice", $invoice)
            ->with("recipts", $query)
            ->with("PackageItem", $setItems);
    }

    public function print()
    {
        $branchCode = auth()->user()->BC;
        $invoice = Item::orderBy('Item_code', 'asc')->get();

        return view('reports.print.item-details', [
            'invoice' => $invoice,
            'PackageItem' => PackageItem::all(),
            'companyData' => Company::latest()->first(),
            'branchDel' => branchDel::where('bccode', $branchCode)->first(),
        ]);
    }
}