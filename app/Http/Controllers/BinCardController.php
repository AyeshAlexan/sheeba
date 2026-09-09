<?php

namespace App\Http\Controllers;
use App\Models\TItemMovement;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class BinCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch_code = auth()->user()->BC;
        $allItemData = Item::all();

        $stockTransferDetails = null;
        $quain = 0;
        $quaout = 0;
        $balance = 0;

        return view('binCard')
            ->with("stockDetails", $stockTransferDetails)
            ->with("allItemData", $allItemData)
            ->with("quain", $quain)
            ->with("quaout", $quaout)
            ->with("balance", $balance);
    }

    public function get(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $allItemData = Item::all();

        $request->validate([
            'to_date'   => 'required',
            'item_code' => 'required',
        ]);

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;
        $itemCode = $request->item_code;
        $itemName = $request->item_description;

        $stockTransferDetails = DB::select("
            SELECT
                im.dDate,
                im.trans_no,
                im.trans_code,
                im.item_code,
                im.trans_code,
                it.Item_description AS item_name,
                im.qun_in,
                im.qun_out,
                COALESCE(s.Invoice_no, r.Invoice_no)       AS invoice_no,
                COALESCE(s.Customer_NIC, r.Customer_NIC)   AS customer_code,
                COALESCE(s.Customer_Name, r.Customer_Name) AS customer_name
            FROM t_item_movements im
            LEFT JOIN items it
                ON it.Item_code = im.item_code
            LEFT JOIN t_without_vat_sales_sums s
                ON s.Invoice_no = im.trans_no AND im.trans_code = 'SALES_OUT_VAT'
            LEFT JOIN t_sales_return_sums r
                ON r.Invoice_no = im.trans_no AND im.trans_code = 'SPN'
            WHERE im.dDate BETWEEN ? AND ?
                AND im.bc = ?
                AND im.item_code = ?
            ORDER BY im.dDate
        ", [$fromDate, $toDate, $branch_code, $itemCode]);

        $quain   = collect($stockTransferDetails)->sum('qun_in');
        $quaout  = collect($stockTransferDetails)->sum('qun_out');
        $balance = $quain - $quaout;

        return view('binCard')
            ->with("allItemData", $allItemData)
            ->with("stockDetails", $stockTransferDetails)
            ->with("fromDate", $fromDate)
            ->with("toDate", $toDate)
            ->with("itemName", $itemName)
            ->with("quain", $quain)
            ->with("quaout", $quaout)
            ->with("balance", $balance);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function SetItemDescriptionBin(Request $request){
        $Item_code = $request->Item_code;
        $data = Item::where('Item_code',$Item_code)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}