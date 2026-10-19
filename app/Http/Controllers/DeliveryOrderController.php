<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\Company;
use App\Models\branchDel;
use App\Models\TWithoutVatSalesSum;
use App\Models\TWithoutVatSalesDetails;
use App\Models\TDeliveryOrderSum;
use App\Models\TDeliveryOrderDetails;
use App\Models\TItemMovement;

class DeliveryOrderController extends Controller
{
    public function index(Request $request)
    {
        $branch_code = auth()->user()->BC;

        $maxDoNo = TDeliveryOrderSum::orderBy('DO_no', 'desc')
            ->where('BC', $branch_code)
            ->value('DO_no');
        $maxDo = str_pad($maxDoNo, 4, '0', STR_PAD_LEFT);

        $companyData = Company::latest()->paginate(1);

        return view('deliveryOrder')
            ->with('companyData', $companyData)
            ->with('maxDoNo', $maxDo);
    }

    // find invoice line items for a given Invoice_no (ajax, returns rendered rows)
    public function findInvoice(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $invoiceNo = $request->search_invoice_no;

        $data = TWithoutVatSalesDetails::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)
            ->get();

        if ($data->count() != 0) {
            return view('delivery_order_find_items')
                ->with('invoiceData', $data);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }

    // find invoice header/customer data for a given Invoice_no (ajax, returns json)
    public function findInvoiceCustomerData(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $invoiceNo = $request->search_invoice_no;

        $data = TWithoutVatSalesSum::where('Invoice_no', $invoiceNo)
            ->where('BC', $branch_code)
            ->get();

        if ($data->count() != 0) {
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }

    public function createDeliveryOrder(Request $request)
    {
        $request->validate([
            'do_no' => 'required',
            'do_date' => 'required',
            'invoice_no' => 'required',
            'inputs' => 'required|array|min:1',
            'inputs.*.item_code' => 'required',
            'inputs.*.qty' => 'required',
            'inputs.*.unit_price' => 'required',
            'inputs.*.net_value' => 'required',
        ], [
            'do_no' => 'The DO No field is required',
            'do_date' => 'The DO Date field is required',
            'invoice_no' => 'The Invoice No field is required',
            'inputs' => 'Look up a valid Invoice No first so at least one item loads before saving',
            'inputs.*.item_code' => 'The Item Code field is required',
            'inputs.*.qty' => 'The QTY field is required',
            'inputs.*.unit_price' => 'The Unit Price field is required',
            'inputs.*.net_value' => 'The Net Value field is required',
        ]);

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        DB::beginTransaction();
        try {
            // Batch check up-front, same SUM(qun_in)-SUM(qun_out) derivation used by
            // Sales Invoice/GRN, so we fail before writing any rows if stock is short.
            foreach ($request->inputs as $value) {
                $batchNo = $value['batch_no'] ?? null;
                if (!empty($batchNo)) {
                    $qtyRemaining = TItemMovement::where('item_code', $value['item_code'])
                        ->where('batch_no', $batchNo)
                        ->selectRaw('COALESCE(SUM(qun_in),0) - COALESCE(SUM(qun_out),0) as remaining')
                        ->value('remaining');
                    if ($value['qty'] > $qtyRemaining) {
                        abort(422, "Not enough stock in batch \"{$batchNo}\" for item {$value['item_code']}. Remaining: {$qtyRemaining}");
                    }
                }
            }

            $doSum = new TDeliveryOrderSum;
            $doSum->DO_no = $request->do_no;
            $doSum->DO_date = $request->do_date;
            $doSum->Invoice_no = $request->invoice_no;
            $doSum->Invoice_date = $request->invoice_date;
            $doSum->Customer_NIC = $request->customer_nic;
            $doSum->Customer_Name = $request->customer_name;
            $doSum->Customer_Phone = $request->customer_phone;
            $doSum->Gross_Amount = $request->gross_amount;
            $doSum->Discount = $request->discount;
            $doSum->Net_Amount = $request->net_amount;
            $doSum->Delivered_By = $request->delivered_by;
            $doSum->Received_By = $request->received_by;
            $doSum->BC = $branch_code;
            $doSum->OC = $user_name;
            $doSum->save();

            foreach ($request->inputs as $value) {
                $doDetails = new TDeliveryOrderDetails;
                $doDetails->DO_no = $request->do_no;
                $doDetails->DO_date = $request->do_date;
                $doDetails->Item_code = $value['item_code'];
                $doDetails->Item_s_code = $value['item_s_code'] ?? null;
                $doDetails->Item_description = $value['item_description'] ?? null;
                $doDetails->QTY = $value['qty'];
                $doDetails->Unit_price = $value['unit_price'];
                $doDetails->Discount = $value['discount'] ?? 0;
                $doDetails->Net_value = $value['net_value'];
                $doDetails->batch_no = $value['batch_no'] ?? null;
                $doDetails->BC = $branch_code;
                $doDetails->OC = $user_name;
                $doDetails->save();

                $movement = new TItemMovement;
                $movement->trans_no = $request->do_no;
                $movement->dDate = $request->do_date;
                $movement->trans_code = 'DO';
                $movement->item_code = $value['item_code'];
                $movement->batch_no = $value['batch_no'] ?? null;
                $movement->qun_out = $value['qty'];
                $movement->bc = $branch_code;
                $movement->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $companyData = Company::latest()->paginate(1);
        $branchDelData = branchDel::where('bccode', $branch_code)->get();

        $doSumData = TDeliveryOrderSum::where('DO_no', $request->do_no)
            ->where('BC', $branch_code)
            ->get();
        $doDetailsData = TDeliveryOrderDetails::where('DO_no', $request->do_no)
            ->where('BC', $branch_code)
            ->get();

        $pdf = PDF::loadView('delivery_order_print', [
            'doSumData' => $doSumData,
            'doDetailsData' => $doDetailsData,
            'companyData' => $companyData,
            'branchDel' => $branchDelData,
        ]);

        $pdfPath = storage_path('../public/assets/pdf/Delivery_Order.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('/assets/pdf/Delivery_Order.pdf');

        return back()
            ->with('done', 'The Delivery Order has been added')
            ->with('pdfLink', $pdfUrl);
    }

    // reprint a saved delivery order
    public function print(Request $request, $do_no)
    {
        $branch_code = auth()->user()->BC;

        $companyData = Company::latest()->paginate(1);
        $branchDelData = branchDel::where('bccode', $branch_code)->get();

        $doSumData = TDeliveryOrderSum::where('DO_no', $do_no)
            ->where('BC', $branch_code)
            ->get();
        $doDetailsData = TDeliveryOrderDetails::where('DO_no', $do_no)
            ->where('BC', $branch_code)
            ->get();

        if ($doSumData->count() == 0) {
            return response()->json(['status' => 'not_found']);
        }

        $pdf = PDF::loadView('delivery_order_print', [
            'doSumData' => $doSumData,
            'doDetailsData' => $doDetailsData,
            'companyData' => $companyData,
            'branchDel' => $branchDelData,
        ]);

        $pdfPath = storage_path('../public/assets/pdf/Delivery_Order.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('/assets/pdf/Delivery_Order.pdf');

        return response()->json(['status' => 'success', 'pdfUrl' => $pdfUrl]);
    }

    public function listDeliveryOrders(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = TDeliveryOrderSum::where('BC', $branch_code);
        if ($fromDate && $toDate) {
            $query->whereBetween('DO_date', [$fromDate, $toDate]);
        }

        $deliveryOrders = $query->orderBy('DO_no', 'desc')->paginate(20);

        return view('delivery_order_list')
            ->with('deliveryOrders', $deliveryOrders)
            ->with('fromDate', $fromDate)
            ->with('toDate', $toDate);
    }

    // delete a delivery order and reverse its stock movement
    public function deleteDeliveryOrder(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $doNo = $request->do_no;

        DB::beginTransaction();
        try {
            $details = TDeliveryOrderDetails::where('DO_no', $doNo)
                ->where('BC', $branch_code)
                ->get();

            foreach ($details as $line) {
                $reversal = new TItemMovement;
                $reversal->trans_no = $doNo;
                $reversal->dDate = now()->toDateString();
                $reversal->trans_code = 'DO_REVERSAL';
                $reversal->item_code = $line->Item_code;
                $reversal->batch_no = $line->batch_no;
                $reversal->qun_in = $line->QTY;
                $reversal->bc = $branch_code;
                $reversal->save();
            }

            TDeliveryOrderDetails::where('DO_no', $doNo)->where('BC', $branch_code)->delete();
            TDeliveryOrderSum::where('DO_no', $doNo)->where('BC', $branch_code)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'status' => 'success',
        ]);
    }
}
