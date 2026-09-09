<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recei_Add;
class ReceiptController extends Controller
{
    public function index(){
        $receipt = Recei_Add::latest()->paginate(5);
        return view('receipt')->with("Recei_Add" , $receipt);
    }

    public function index_forfeit_receipt(){
        return view('ForfeitReceipt');
    }


    function add_receipt(Request $request){
        $request->validate([
            'receiptname'=>'required | max:20 ',
            'rate1'=>'required | max:5 ',
            'rate2'=>'required | max:5 ',
            'rate2'=>'required | max:5 ',
            'period1'=>'required | max:2 ',
            'period2'=>'required | max:2 ',
            'period3'=>'required | max:2 ',
        ]);
        $receipt = new Recei_Add;
        $receipt->receiptname = $request->receiptname;
        $receipt->rate1 = $request->rate1;
        $receipt->period1 = $request->period1;
        $receipt->rate2 = $request->rate2;
        $receipt->period2 = $request->period2;
        $receipt->rate3 = $request->rate3;
        $receipt->period3 = $request->period3;
        $receipt->save();
        return redirect()->back()->with("Recei_Add" , $receipt)->with("added", "Receipt added");
    }


    // ------------- Create receipt using Ajex-------------
    public function create(Request $request){
        $request->validate([
            'receiptname'=>'required | max:12 | unique:recei__adds',
            'rate1'=>'required | max:5 ',
            'rate2'=>'required | max:5 ',
            'rate2'=>'required | max:5 ',
            'period1'=>'required | max:2 ',
            'period2'=>'required | max:2 ',
            'period3'=>'required | max:2 ',
        ]);
        $receipt = new Recei_Add;
        $receipt->receiptname = $request->receiptname;
        $receipt->rate1 = $request->rate1;
        $receipt->period1 = $request->period1;
        $receipt->rate2 = $request->rate2;
        $receipt->period2 = $request->period2;
        $receipt->rate3 = $request->rate3;
        $receipt->period3 = $request->period3;
        $receipt->save();

        return response()->json([
            'status'=>'success',
        ]);
    }

    // ------------- delete receipt using Ajex-------------
    public function delete(Request $request)
    {
        Recei_Add::find($request->receipt_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }




    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request)
    {
        $request->validate([
            'up_receiptname'=>'required | max:12 | unique:recei__adds,receiptname,'.$request->up_id,
            'up_rate1'=>'required | max:5 ',
            'up_rate2'=>'required | max:5 ',
            'up_rate3'=>'required | max:5 ',
            'up_period1'=>'required | max:2 ',
            'up_period2'=>'required | max:2 ',
            'up_period3'=>'required | max:2 ',
        ]);

        Recei_Add::where('id',$request->up_id)->update([
            'receiptname'=>$request->up_receiptname,
            'rate1'=>$request->up_rate1,
            'rate2'=>$request->up_rate2,
            'rate3'=>$request->up_rate3,
            'period1'=>$request->up_period1,
            'period2'=>$request->up_period2,
            'period3'=>$request->up_period3,

        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

    public function destroy($id)
    {
        $data = Recei_Add::find($id);
        $data->delete();
        return redirect()->back()->with("delete", "Receipt deleted");
    }
}
