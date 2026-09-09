<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TInvoiceSum;
use App\Models\THirePurchaseSum;
use App\Models\THirePurchaseDetails;
use App\Models\TInvoiceDeils;
use App\Models\TItemMovement;
use App\Models\TItemSerialMovement;
use App\Models\TAccountTrans;
use App\Models\TCusSaleTrance;
use App\Models\TAdvancCusPayment;
use App\Models\TInstalment;
use App\Models\THirePurchaseToSales;

class CashSalesConversionController extends Controller
{

    //index of
    public function index()
    {
        $branch_code = auth()->user()->BC;
        // get max invoice number max
        $maxInvoiceNo = THirePurchaseToSales::orderBy('conversion_no', 'desc')
        ->where('BC',$branch_code)
        ->value('conversion_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        return view('cash_sales_conversion')
            ->with("maxInvoiceNo", $maxInvoice);
    }

    //create HP to cash sales
    public function create(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $invoiceNo = $request->invoice_no;

        TInstalment::where('invoice_no',$invoiceNo)
                    ->where('bc', $branch_code)
                    ->delete();

        $hp_to_sales = new THirePurchaseToSales;
        $hp_to_sales->conversion_no = $request->conversion_no;
        $hp_to_sales->conversion_date = $request->conversion_date;
        $hp_to_sales->invoice_no = $request->invoice_no;
        $hp_to_sales->agreement_no = $request->agreement_no;
        $hp_to_sales->invoice_date = $request->invoice_date;

        $hp_to_sales->customer_nic = $request->customer_nic;
        $hp_to_sales->customer_name = $request->customer_name;
        $hp_to_sales->customer_phone = $request->customer_phone;

        $hp_to_sales->item_net_amount = $request->item_net_amount;
        $hp_to_sales->down_payment = $request->item_down_payment;
        $hp_to_sales->transport = $request->item_transport;
        $hp_to_sales->total_instalment_amount = $request->item_total_installment;
        $hp_to_sales->due_amount_as_discount = $request->item_due_amount;
        $hp_to_sales->discount = $request->item_due_amount;
        $hp_to_sales->amount = $request->net_amount;

        $hp_to_sales->cash_payment = $request->cash_payment;
        $hp_to_sales->card_payment = $request->card_payment;
        $hp_to_sales->cheque_payment = $request->cheque_payment;
        $hp_to_sales->bank_transfer = $request->bank_transfer;
        $hp_to_sales->oc = auth()->user()->username;
        $hp_to_sales->bc = auth()->user()->BC;
        $hp_to_sales->save();

        THirePurchaseSum::where('invoice_no',$invoiceNo)->update([
                        'is_cash_converted'=>1]);

        THirePurchaseDetails::where('invoice_no',$invoiceNo)->update([
                        'is_cash_converted'=>1]);

        // $installment_data = TInstalment::where('invoice_no',$invoiceNo)
        //                     ->where('bc', $branch_code)
        //                     ->latest()
        //                     ->paginate(1);

        // if($installment_data){
        //     foreach ($installment_data as $data1) {
        //         $instalment = new TInstalment;
        //         $instalment->invoice_no = $data1->invoice_no;
        //         $instalment->agreement_no = $data1->agreement_no;
        //         $instalment->invoice_date = $data1->invoice_date;
        //         $instalment->customer_code = $data1->customer_code;
        //         $instalment->customer_name = $data1->customer_name;
        //         $instalment->schema_type = $data1->schema_type;

        //         // Calculate instalment_date
        //         $instalment->instalment_date = Carbon::now()->format('Y-m-d');
        //         $instalment->instalment_amount = $request->net_amount;
        //         $instalment->discount = $request->paid_discount;
        //         $instalment->amount_pay = $request->net_amount;
        //         $instalment->cash_payment = $request->net_amount;
        //         $instalment->oc = auth()->user()->username;
        //         $instalment->bc = auth()->user()->BC;
        //         $instalment->save();
        //     }
        // }

        return back()
        ->with('done','The Sales Conversion is Success..!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
