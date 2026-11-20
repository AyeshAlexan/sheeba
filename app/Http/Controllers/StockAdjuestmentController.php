<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Store;
use App\Models\TItemMovement;
use App\Models\StockAdjuestmentSum;
use App\Models\StockAdjuestmentDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockAdjuestmentController extends Controller
{
    private function branchBalances($branch)
    {
        return TItemMovement::where('bc', $branch)->select('item_code')
            ->selectRaw('COALESCE(SUM(qun_in), 0) - COALESCE(SUM(qun_out), 0) AS balance')
            ->groupBy('item_code');
    }

    public function indexShow()
    {
        $branch = auth()->user()->BC;
        $items = Item::leftJoinSub($this->branchBalances($branch), 'stock', 'stock.item_code', '=', 'items.Item_code')
            ->select('items.Item_code', 'items.Item_description')
            ->selectRaw('COALESCE(stock.balance, 0) AS QTY')
            ->orderBy('items.Item_code')->get();

        return view('stockAdjuestmentNew', [
            'itemDetails' => $items,
            'maxInvoiceNo' => StockAdjuestmentSum::where('BC', $branch)->max('Invoice_no') ?? 0,
            'storeDta' => Store::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Store_StockAdjuestment(Request $request)
    {
        $lines = $request->input('lines');
        $request->merge(['lines' => is_string($lines) ? json_decode($lines, true) : $lines]);
        $data = $request->validate([
            'invoice_no' => 'required|integer|min:1',
            'invoice_date' => 'required|date_format:Y-m-d',
            'Store_code' => 'required|exists:stores,Store_code',
            'lines' => 'required|array|min:1',
            'lines.*.item_code' => 'required|string|distinct:ignore_case',
            'lines.*.action' => 'required|in:increase,reduce',
            'lines.*.quantity' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'lines.*.balance' => 'required|numeric',
        ]);
        $user = $request->user();

        DB::transaction(function () use ($data, $user) {
            $codes = array_column($data['lines'], 'item_code');
            $items = Item::whereIn('Item_code', $codes)->orderBy('Item_code')->lockForUpdate()->get()->keyBy('Item_code');
            $existing = StockAdjuestmentSum::where('BC', $user->BC)->where('Invoice_no', $data['invoice_no'])
                ->lockForUpdate()->first();
            if ($existing) {
                throw ValidationException::withMessages(['invoice_no' => 'This adjustment number has already been used. Review your changes before saving again.']);
            }
            $balances = $this->branchBalances($user->BC)->whereIn('item_code', $codes)->pluck('balance', 'item_code');
            $header = [
                'Invoice_no' => $data['invoice_no'], 'Invoice_date' => $data['invoice_date'],
                'Store_code' => $data['Store_code'], 'BC' => $user->BC, 'OC' => $user->username,
            ];
            $total = 0;
            foreach ($data['lines'] as $line) {
                $code = $line['item_code'];
                $item = $items->get($code);
                if (!$item) {
                    throw ValidationException::withMessages(['lines' => "Item $code is unavailable."]);
                }
                $current = round((float) $balances->get($code, 0), 2);
                if ($current !== (float) $line['balance']) {
                    throw ValidationException::withMessages(['lines' => "$code now has $current units. Review the updated balance and save again."]);
                }
                $quantity = (float) $line['quantity'];
                $increase = $line['action'] === 'increase';
                if (!$increase && $quantity > $current) {
                    throw ValidationException::withMessages(['lines' => "$code has only $current units available."]);
                }
                $change = $increase ? $quantity : -$quantity;
                // Existing detail records store the ending count and value shortages positively.
                $value = round(-$change * $item->saleprice, 2);
                $total += $value;
                (new StockAdjuestmentDetails)->forceFill($header + [
                    'Item_code' => $code, 'Item_description' => $item->Item_description,
                    'QTY' => $current, 'qtyOut' => $current + $change,
                    'Unit_price' => $item->saleprice, 'Net_value' => $value,
                ])->save();
                (new TItemMovement)->forceFill([
                    'trans_no' => $data['invoice_no'], 'dDate' => $data['invoice_date'],
                    'trans_code' => 'STOCK_ADJUSTMENT', 'item_code' => $code,
                    'qun_in' => $increase ? $quantity : 0, 'qun_out' => $increase ? 0 : $quantity,
                    'Stock_adjuestment' => -$change, 'bc' => $user->BC, 'storse_id' => $data['Store_code'],
                ])->save();
            }
            (new StockAdjuestmentSum)->forceFill($header + ['Amount' => $total])->save();
        }, 3);

        return redirect()->route('stockAdjuestmentNew')->with('success', 'Stock adjustment saved.');
    }

}
