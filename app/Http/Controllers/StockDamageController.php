<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockDamageSum;
use App\Models\StockDamageDetail;
use App\Models\TItemMovement;
use App\Models\Store;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class StockDamageController extends Controller
{
    /**
     * Stock Damage — internal inventory loss (broken/expired/lost items).
     * No customer or invoice is involved; for a customer returning goods
     * they bought, see Sales Return instead, which already handles
     * restocking + crediting that customer's account.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(StockDamageSum::orderBy('Damage_date', 'desc')->select('*'))
                ->addColumn('reasons', function ($row) {
                    return StockDamageDetail::where('Damage_no', $row->Damage_no)
                        ->pluck('Reason')
                        ->filter()
                        ->unique()
                        ->implode(', ');
                })
                ->addColumn('action', 'Action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $branchCode = auth()->user()->BC;
        $stores = Store::all();
        $items = Item::orderBy('Item_description')->get(['Item_code', 'Item_description', 'purchasePrice']);
        $nextDamageNo = 'DMG-' . str_pad((StockDamageSum::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT);

        return view('stockDamage', [
            'stores'       => $stores,
            'items'        => $items,
            'nextDamageNo' => $nextDamageNo,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'damage_no'   => 'required|string',
            'damage_date' => 'required|date',
            'store_code'  => 'required|string',
            'items'       => 'required|array|min:1',
            'items.*.item_code'        => 'required|string',
            'items.*.item_description' => 'nullable|string',
            'items.*.unit_price'       => 'required|numeric|min:0',
            'items.*.qty'              => 'required|numeric|min:0.01',
            'items.*.reason'           => 'required|string|max:255',
        ], [
            'store_code.required'      => 'Store is required.',
            'items.*.unit_price.required' => 'Unit price is required for every item.',
            'items.*.reason.required'     => 'A reason is required for every item.',
        ]);

        $branchCode = auth()->user()->BC;
        $userName   = auth()->user()->username;
        $damageNo   = $request->damage_no;

        DB::beginTransaction();
        try {
            // t_item_movements.trans_no is a bigint, so the human-readable
            // "DMG-00001" label can't go there — the header row's own
            // numeric id is used instead, and stays stable across edits.
            $sum = StockDamageSum::where('Damage_no', $damageNo)->first();
            $isEdit = (bool) $sum;

            if (!$sum) {
                $sum = new StockDamageSum();
                $sum->Damage_no = $damageNo;
                $sum->Damage_date = $request->damage_date;
                $sum->BC = $branchCode;
                $sum->OC = $userName;
                $sum->save();
            }

            // Editing re-posts from scratch — remove what this damage entry
            // previously did to stock before reapplying, the same pattern
            // used for Daily Transactions, so editing never double-counts.
            if ($isEdit) {
                StockDamageDetail::where('Damage_no', $damageNo)->delete();
                TItemMovement::where('trans_no', $sum->id)->where('trans_code', 'STOCK_DAMAGE')->delete();
            }

            $totalValue = 0;
            $totalQty   = 0;

            foreach ($request->items as $line) {
                $qty       = (float) $line['qty'];
                $unitPrice = (float) ($line['unit_price'] ?? 0);
                $netValue  = $qty * $unitPrice;
                $totalValue += $netValue;
                $totalQty   += $qty;

                // A damage entry can't remove more than what's actually on
                // hand — computed fresh from the ledger, not trusted from
                // the browser.
                $onHand = TItemMovement::where('item_code', $line['item_code'])
                    ->where('bc', $branchCode)
                    ->selectRaw('COALESCE(SUM(qun_in),0) - COALESCE(SUM(qun_out),0) as balance')
                    ->value('balance') ?? 0;

                if ($qty > $onHand) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => "Cannot damage {$qty} of {$line['item_code']} — only {$onHand} in stock.",
                    ], 422);
                }

                StockDamageDetail::create([
                    'Damage_no'        => $damageNo,
                    'Item_code'        => $line['item_code'],
                    'Item_description' => $line['item_description'] ?? null,
                    'QTY'              => $qty,
                    'Unit_price'       => $unitPrice,
                    'Net_value'        => $netValue,
                    'Reason'           => $line['reason'] ?? null,
                    'BC'               => $branchCode,
                    'OC'               => $userName,
                ]);

                $movement = new TItemMovement();
                $movement->trans_no   = $sum->id;
                $movement->trans_code = 'STOCK_DAMAGE';
                $movement->item_code  = $line['item_code'];
                $movement->qun_in     = 0;
                $movement->qun_out    = $qty;
                $movement->dDate      = $request->damage_date;
                $movement->bc         = $branchCode;
                $movement->save();
            }

            $sum->Damage_date = $request->damage_date;
            $sum->Store_code  = $request->store_code;
            $sum->Total_qty   = $totalQty;
            $sum->Total_value = $totalValue;
            $sum->save();

            DB::commit();
            return response()->json(['status' => 'success', 'damage_no' => $damageNo]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Live stock-on-hand for one item, used by the item picker so a user
     * sees what's available before typing a damaged quantity.
     */
    public function itemBalance(Request $request)
    {
        $branchCode = auth()->user()->BC;

        $balance = TItemMovement::where('item_code', $request->item_code)
            ->where('bc', $branchCode)
            ->selectRaw('COALESCE(SUM(qun_in),0) - COALESCE(SUM(qun_out),0) as balance')
            ->value('balance') ?? 0;

        return response()->json(['balance' => $balance]);
    }

    public function edit(Request $request)
    {
        $sum = StockDamageSum::where('id', $request->id)->first();

        if (!$sum) {
            return response()->json(['status' => 'error', 'message' => 'Not found.'], 404);
        }

        $details = StockDamageDetail::where('Damage_no', $sum->Damage_no)->get();

        return response()->json(['sum' => $sum, 'details' => $details]);
    }

    public function destroy(Request $request)
    {
        $sum = StockDamageSum::where('id', $request->id)->first();

        if (!$sum) {
            return response()->json(['status' => 'error', 'message' => 'Not found.'], 404);
        }

        DB::transaction(function () use ($sum) {
            StockDamageDetail::where('Damage_no', $sum->Damage_no)->delete();
            TItemMovement::where('trans_no', $sum->id)->where('trans_code', 'STOCK_DAMAGE')->delete();
            $sum->delete();
        });

        return response()->json(['status' => 'success']);
    }
}
