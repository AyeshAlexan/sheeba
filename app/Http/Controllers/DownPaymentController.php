<?php
namespace App\Http\Controllers;
use App\Models\DownPayment;
use Illuminate\Http\Request;


class DownPaymentController extends Controller
{

    public function index()
    {

    }


    public function create()
    {

    }

    //  add down payment ajax
    public function store(Request $request)
    {
        $request->validate([
            'down_payment_no'=>'required',
            'down_agreement_no'=>'required',
            'down_payment_date'=>'required',
            'down_payment_customer_name'=>'required | max:200 ',
            'down_payment_cus_code'=>'required',
            'down_payment_description'=>' max:400 ',
            'down_payment_amount'=>' required ',
        ]);

        $downPayment = new DownPayment();
        $downPayment->payment_no=$request->down_payment_no;
        $downPayment->agreement_no=$request->down_agreement_no;
        $downPayment->payment_date=$request->down_payment_date;
        $downPayment->customer_name=$request->down_payment_customer_name;
        $downPayment->cus_code=$request->down_payment_cus_code;
        $downPayment->description=$request->down_payment_description;
        $downPayment->amount=$request->down_payment_amount;
        $downPayment->bc = auth()->user()->BC;
        $downPayment->oc = auth()->user()->username;
        $downPayment->save();

        return response()->json([
            'status'=>'success',
        ]);
    }


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
