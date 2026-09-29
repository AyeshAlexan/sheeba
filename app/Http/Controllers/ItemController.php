<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Department;
use App\Models\MBrand;
use App\Models\MColor;
use App\Models\M_Make;
use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Item::select('*'))
                ->addColumn('action', 'Action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('Item')
            ->with('Category',   Category::all())
            ->with('Department', Department::all())
            ->with('Brand',      MBrand::all())
            ->with('Color',      MColor::all())
            ->with('Make',       M_Make::all());
    }

    // ── Single item store / update ─────────────────────────────────
    public function Itemstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Item_code'        => ['required', 'max:25', Rule::unique('items', 'Item_code')->ignore($request->id)],
            'Item_description' => 'required',
            'purchasePrice'    => 'required|numeric|min:0',
            'saleprice'        => 'required|numeric|min:0',
        ], [
            'Item_code.unique' => 'This item code is already in use — each item must have a unique code.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Item::updateOrCreate(
            ['id' => $request->id],
            [
                'category'          => $request->category,
                'Department'        => $request->Department,
                'Item_code'         => $request->Item_code,
                'Bar_code'          => $request->Bar_code,
                'Item_description'  => $request->Item_description,
                'Brand'             => $request->Brand,
                'Color'             => $request->Color,
                'Make'              => $request->Make,
                'purchasePrice'     => $request->purchasePrice,
                'saleprice'         => $request->saleprice,
                'Credit'            => $request->Credit,
                'Inactive'          => $request->Inactive,
                'ReorderLevel'      => $request->ReorderLevel,
                'RecorderQuantitiy' => $request->RecorderQuantitiy,
                'SaleDecimal'       => $request->SaleDecimal,
                'Serialnumber'      => $request->Serialnumber,
                'Per'      => $request->Per,
                'Branch'            => $request->Branch,
                'BranchCode'        => $request->BranchCode,
            ]
        );

        return response()->json(['message' => 'Item saved successfully']);
    }

    // ── Bulk store ─────────────────────────────────────────────────
    public function ItemBulkStore(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['message' => 'No items provided'], 422);
        }

        $saved  = 0;
        $errors = [];

        foreach ($items as $index => $item) {
            $validator = Validator::make($item, [
                'Item_description' => 'required',
                'purchasePrice'    => 'required|numeric',
                'saleprice'        => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $errors[] = 'Row ' . ($index + 1) . ': ' . implode(', ', $validator->errors()->all());
                continue;
            }

            Item::create([
                'category'         => $item['category']         ?? null,
                'Department'       => $item['Department']       ?? null,
                'Item_code'        => $item['Item_code']        ?? null,
                'Bar_code'         => $item['Bar_code']         ?? null,
                'Item_description' => $item['Item_description'],
                'purchasePrice'    => $item['purchasePrice'],
                'saleprice'        => $item['saleprice'],
                'Credit'           => $item['Credit']           ?? null,
                'Branch'           => $item['Branch']           ?? null,
                'BranchCode'       => $item['BranchCode']       ?? null,
            ]);

            $saved++;
        }

        if (!empty($errors)) {
            return response()->json(['message' => implode(' | ', $errors)], 422);
        }

        return response()->json(['message' => $saved . ' item(s) saved successfully!']);
    }

    // ── Items by category ──────────────────────────────────────────
    public function ItemsByCategory(Request $request)
    {
        $items = Item::where('category', $request->category)
            ->select('Item_code', 'Item_description', 'purchasePrice', 'saleprice')
            ->orderBy('Item_code', 'desc')
            ->get();

        return response()->json($items);
    }

    // ── Item search for package autocomplete ───────────────────────
    public function ItemSearch(Request $request)
    {
        $q = $request->q;

        $items = Item::where('Item_description', 'like', '%' . $q . '%')
            ->orWhere('Item_code', 'like', '%' . $q . '%')
            ->orWhere('Bar_code',  'like', '%' . $q . '%')
            ->select('Item_code', 'Item_description', 'purchasePrice', 'saleprice', 'Credit')
            ->orderBy('Item_description')
            ->limit(15)
            ->get();

        return response()->json($items);
    }

    // ── Package store ──────────────────────────────────────────────
    //
    //  Does THREE things in one request:
    //
    //  1. Saves a record to the `packages` table (package header).
    //  2. Saves each item in `items[]` to `package_items` table — including qty.
    //  3. Saves ONE record to the `items` table where:
    //       Item_description = package_name  (the "Set Item Name" field)
    //       Item_code        = pkg_code      (the "Set Item Code" field)
    //       purchasePrice    = manually typed Total Purchase Price
    //       saleprice        = manually typed Total Sale Unit Price
    //       Credit           = manually typed Total Border Price
    //       Item_set_bulk    = pipe-separated "CODE(QTY)" string
    //                          e.g. "SS-MA008(2)|BOWL-001(1)"
    //       Branch / BranchCode from auth
    //
    public function PackageStore(Request $request)
    {
        // ── Validate ─────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'package_name'             => 'required|max:150',
            'pkg_code'                 => 'required|max:25',
            'purchasePrice'            => 'required|numeric|min:0',
            'saleprice'                => 'required|numeric|min:0',
            'Credit'                   => 'nullable|numeric|min:0',
            'Item_set_bulk'            => 'nullable|string|max:750',
            // items array is optional — bundle can exist with 0 child items
            'items'                    => 'nullable|array',
            'items.*.item_code'        => 'required_with:items|string',
            'items.*.item_description' => 'required_with:items|string',
            'items.*.qty'              => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ── 1. Create package header ──────────────────────────────
        $package = Package::create([
            'package_name' => $request->package_name,
            'pkg_code'     => $request->pkg_code,
            'Branch'       => $request->Branch,
            'BranchCode'   => $request->BranchCode,
        ]);

        // ── 2. Save child items to package_items (with qty) ───────
        $itemCount = 0;
        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                PackageItem::create([
                    'package_id'       => $package->id,
                    'pkg_code'         => $request->pkg_code,
                    'item_code'        => $item['item_code'],
                    'item_description' => $item['item_description'],
                    'qty'              => isset($item['qty']) ? (int) $item['qty'] : 1,  // ← qty
                ]);
                $itemCount++;
            }
        }

        // ── 3. Save ONE record to items table ─────────────────────
        //  pkg_name      → Item_description
        //  pkg_code      → Item_code
        //  Manual prices from the summary bar inputs
        //  Item_set_bulk → pipe-separated "CODE(QTY)" e.g. "SS-MA008(2)|BOWL-001(1)"
        Item::create([
            'Item_description'  => $request->package_name,             // pkg_name
            'Item_code'         => $request->pkg_code,                  // pkg_code
            'purchasePrice'     => $request->purchasePrice,             // Total Purchase Price (manual)
            'saleprice'         => $request->saleprice,                 // Total Sale Unit Price (manual)
            'Credit'            => $request->Credit ?? 0,               // Total Border Price (manual)
            'Item_set_bulk'     => $request->Item_set_bulk ?? null,     // ← "SS-MA008(2)|BOWL-001(1)"
            'Branch'            => $request->Branch,
            'BranchCode'        => $request->BranchCode,
            // nullable fields — left null for package-type items
            'category'          => 'Set Item',
            'Department'        => null,
            'Bar_code'          => null,
            'Brand'             => null,
            'Color'             => null,
            'Make'              => null,
            'Inactive'          => 0,
            'ReorderLevel'      => null,
            'RecorderQuantitiy' => null,
            'SaleDecimal'       => 0,
            'Serialnumber'      => 0,
        ]);

        return response()->json([
            'message'    => 'Package "' . $package->package_name . '" saved with ' .
                            $itemCount . ' item(s) and added to items list!',
            'package_id' => $package->id,
        ]);
    }

    // ── Edit ───────────────────────────────────────────────────────
    public function Itemedit(Request $request)
    {
        return response()->json(Item::where('id', $request->id)->first());
    }

    // ── Delete ─────────────────────────────────────────────────────
    public function Itemdelete(Request $request)
    {
        Item::where('id', $request->id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // ── Search ─────────────────────────────────────────────────────
    public function search(Request $request)
    {
        $itemsData = Item::where('Item_description', 'like', '%' . $request->search_string . '%')
            ->orWhere('Item_code', 'like', '%' . $request->search_string . '%')
            ->orWhere('Bar_code',  'like', '%' . $request->search_string . '%')
            ->orderBy('Item_code', 'desc')
            ->paginate(20);

        if ($itemsData->count() >= 1) {
            return view('item_details_pagination')->with('itemDetails', $itemsData)->render();
        }

        return response()->json(['status' => 'not_found']);
    }

    // ── Search purchase price ──────────────────────────────────────
    public function searchPurchasePrice(Request $request)
    {
        $itemsData = Item::where('Item_description', 'like', '%' . $request->search_string . '%')
            ->orWhere('Item_code', 'like', '%' . $request->search_string . '%')
            ->orWhere('Bar_code',  'like', '%' . $request->search_string . '%')
            ->orderBy('Item_code', 'desc')
            ->paginate(20);

        if ($itemsData->count() >= 1) {
            return view('item_details_pagination_purchase_price')->with('itemDetails', $itemsData)->render();
        }

        return response()->json(['status' => 'not_found']);
    }

    // ── Get by code ────────────────────────────────────────────────
    public function get(Request $request)
    {
        $data = Item::where('Item_code', $request->search_string)->get();

        if ($data->count()) {
            return view('Item_search')->with('Item_get', $data)->render();
        }

        return response()->json(['status' => 'not_found']);
    }
}