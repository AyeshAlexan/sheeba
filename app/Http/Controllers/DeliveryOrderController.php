<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Company;
use App\Models\branchDel;
use App\Models\TDeliveryOrderSum;
use App\Models\TDeliveryOrderDetails;

// Delivery Order is retired. Sales Invoice saving now handles stock.
// Creation, lookup, and reversal are gone;
// only print/list remain so historical DOs (real production data) stay
// viewable and reprintable.
class DeliveryOrderController extends Controller
{
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
}
