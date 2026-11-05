<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Support\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function index()
    {
        abort_unless(Permissions::can('sales'), 403);

        return app(SalesInvoicewithoutVatController::class)->orderForm();
    }

    public function show($order)
    {
        abort_unless(Permissions::can('sales'), 403);
        $record = DB::table('sales_orders')->where('bc', auth()->user()->BC)->where('id', $order)->first();
        abort_unless($record, 404, 'Sales order not found.');

        return response()->json(['id' => $record->id, 'invoice_no' => $record->invoice_no,
            'payload' => json_decode($record->payload, true)]);
    }

    public function printOrder($order)
    {
        abort_unless(Permissions::canDo('sales', 'print'), 403);
        $record = DB::table('sales_orders')->where('bc', auth()->user()->BC)->where('id', $order)->first();
        abort_unless($record, 404);

        $payload = json_decode($record->payload, true);
        $gross = $discount = $net = 0;
        $lines = collect($payload['inputs'])->map(function ($line) use (&$gross, &$discount, &$net) {
            $lineGross = (float) $line['qty'] * (float) $line['unit_price'];
            $lineDiscount = round($lineGross * (float) ($line['discount'] ?? 0) / 100, 2);
            $lineNet = round($lineGross - $lineDiscount, 2);
            $gross += $lineGross;
            $discount += $lineDiscount;
            $net += $lineNet;

            return (object) [
                'Item_code' => $line['item_code'],
                'Item_description' => $line['item_description'] ?? '',
                'QTY' => $line['qty'],
                'Unit_price' => $line['unit_price'],
                'Per' => $line['Per'] ?? '',
                'Net_value' => $lineNet,
                'Free_Issues' => $line['Free_Issues'] ?? 0,
            ];
        });
        $summary = (object) [
            'Invoice_no' => $record->id,
            'Invoice_date' => $payload['invoice_date'],
            'Customer_Name' => $payload['customer_name'] ?? '',
            'Customer_Phone' => $payload['customer_phone'] ?? '',
            'Gross_Amount' => round($gross, 2),
            'Discount' => round($discount, 2),
            'Net_Amount' => round($net, 2),
            'invoice_remark' => $payload['invoice_remark'] ?? '',
        ];

        return view('salesOrderPrint', [
            'order' => $record,
            'companyData' => Company::latest()->limit(1)->get(),
            'customerData' => Customer::where('Code', $payload['customer_nic'])
                ->where('BC', auth()->user()->BC)->get(),
            'pawnSumData' => collect([$summary]),
            'pawnDetailsData' => $lines,
        ]);
    }

    public function report(Request $request)
    {
        abort_unless(Permissions::canViewReport('sales_order_report'), 403);

        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        $status      = $request->input('status');
        $customer    = $request->input('customer');
        $branch_code = auth()->user()->BC;

        $orders = DB::table('sales_orders')->where('bc', $branch_code)
            ->when($fromDate && $toDate, fn ($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate]))
            ->when($status === 'open', fn ($q) => $q->whereNull('invoice_no'))
            ->when($status === 'invoiced', fn ($q) => $q->whereNotNull('invoice_no'))
            ->orderByDesc('id')
            ->get();

        $rows = $orders->map(function ($order) {
            $payload = json_decode($order->payload, true) ?: [];

            return (object) [
                'id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'created_at' => $order->created_at,
                'created_by' => $order->created_by,
                'customer_nic' => $payload['customer_nic'] ?? '',
                'customer_name' => $payload['customer_name'] ?? '',
                'item_count' => count($payload['inputs'] ?? []),
                'gross_amount' => (float) ($payload['gross_amount'] ?? 0),
                'discount' => (float) ($payload['discount'] ?? 0),
                'net_amount' => (float) ($payload['net_amount'] ?? 0),
            ];
        });

        if ($customer) {
            $rows = $rows->filter(fn ($row) => str_contains(strtolower($row->customer_nic), strtolower($customer))
                || str_contains(strtolower($row->customer_name), strtolower($customer)))->values();
        }

        $openCount     = $rows->whereNull('invoice_no')->count();
        $invoicedCount = $rows->whereNotNull('invoice_no')->count();

        return view('reports.sales_order_report', [
            'fromDate'      => $fromDate,
            'toDate'        => $toDate,
            'status'        => $status,
            'customer'      => $customer,
            'rows'          => $rows,
            'openCount'     => $openCount,
            'invoicedCount' => $invoicedCount,
            'totalGross'    => number_format($rows->sum('gross_amount'), 2),
            'totalDiscount' => number_format($rows->sum('discount'), 2),
            'totalNet'      => number_format($rows->sum('net_amount'), 2),
            'companyData'   => Company::latest()->first(),
            'branchDel'     => \App\Models\branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Permissions::canDo('sales', 'save'), 403);
        $request->validate([
            'customer_nic' => 'required|string', 'invoice_date' => 'required|date',
            'inputs' => 'required|array|min:1',
            'inputs.*.item_code' => 'required|string|exists:items,Item_code',
            'inputs.*.qty' => 'required|numeric|min:0.001',
            'inputs.*.unit_price' => 'required|numeric|min:0',
            'inputs.*.discount' => 'nullable|numeric|min:0|max:100',
            'sales_order_id' => 'nullable|integer',
        ]);
        $lines = $request->input('inputs');
        $gross = $discount = $net = 0;
        foreach ($lines as &$line) {
            $lineGross = (float) $line['qty'] * (float) $line['unit_price'];
            $line['discount'] = (float) ($line['discount'] ?? 0);
            $line['discount_val'] = round($lineGross * $line['discount'] / 100, 2);
            $line['net_value'] = round($lineGross - $line['discount_val'], 2);
            $gross += $lineGross;
            $discount += $line['discount_val'];
            $net += $line['net_value'];
        }
        unset($line);
        $request->merge(['inputs' => $lines, 'gross_amount' => round($gross, 2),
            'discount' => round($discount, 2), 'net_amount' => round($net, 2)]);
        $id = DB::transaction(function () use ($request) {
            $record = null;
            if ($request->sales_order_id) {
                abort_unless(Permissions::canDo('sales', 'edit'), 403);
                $record = DB::table('sales_orders')->where('bc', auth()->user()->BC)
                    ->where('id', $request->sales_order_id)->lockForUpdate()->first();
                abort_unless($record, 404);
                abort_if($record->invoice_no !== null, 409, 'This order has already been invoiced.');
            } else {
                abort_unless(Permissions::canDo('sales', 'add'), 403);
            }
            $data = ['bc' => auth()->user()->BC,
                'payload' => json_encode($request->only(['customer_nic', 'customer_name', 'customer_phone',
                    'invoice_date', 'Route', 'Salesmen', 'ref_no', 'customer_type', 'invoice_remark', 'inputs',
                    'gross_amount', 'discount', 'net_amount'])),
                'updated_at' => now()];
            if ($record) {
                DB::table('sales_orders')->where('id', $record->id)->update($data);

                return $record->id;
            }
            $data['created_by'] = auth()->user()->username;
            $data['created_at'] = now();

            return DB::table('sales_orders')->insertGetId($data);
        });

        return redirect()->route('sales_orders')->with('added', 'Sales Order #'.$id.' saved successfully.')->with('saved_order_id', $id);
    }
}
