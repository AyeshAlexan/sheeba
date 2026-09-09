<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\itemCondition;
use App\Models\karatage;
use App\Models\Recei_Add;
use App\Models\Category;
use App\Models\Customer;
use App\Models\TPawnDetails;
use App\Models\TPawnSum;
use App\Models\TRedeemSum;

class RedeemController extends Controller
{

    public function index()
    {
        $maxRedeemNo = TRedeemSum::orderBy('Redeem_Number', 'desc')->value('Redeem_Number');
        $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

        return view('RedeemReceipt')
        ->with("maxRedeem", $maxRedeemNos);
    }

    public function search(Request $request)
    {
        $receiptNo = $request->search_receipt_no;
        $data = TPawnSum::where('Receipt_Number',$receiptNo)->get();

        if($data->count()>0){

            $cus_nic = $data->first()->Customer_NIC;
            $cus_data = Customer::where('NIC', $cus_nic)->get();

            $receipt_typ = $data->first()->Receipt_Type;
            $receipt_data = Recei_Add::where('receiptname', $receipt_typ)->get();

            $maxRedeemNo = TRedeemSum::orderBy('Redeem_Number', 'desc')->value('Redeem_Number');
            $maxRedeemNos = str_pad($maxRedeemNo, 4, '0', STR_PAD_LEFT);

            return view('RedeemSearch')
            ->with("maxRedeem", $maxRedeemNos)
            ->with('customerData', $cus_data)
            ->with('receiptTypeData', $receipt_data)
            ->with('receiptData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }



    public function create()
    {

    }


    public function store(Request $request)
    {
        $NewRedeem = new TRedeemSum;
        $NewRedeem->Receipt_Number = $request->receipt_number;
        $NewRedeem->Redeem_Date = $request->redeem_date;
        $NewRedeem->Redeem_Number = $request->redeem_no;
        $NewRedeem->Original_Pawn_Amount = $request->original_pawn_amount;
        $NewRedeem->Payable_Pawn_Amount = $request->original_pawn_amount;
        $NewRedeem->Paid_Interest = $request->paid_interest;
        $NewRedeem->Payable_Interest = $request->payable_interest;
        $NewRedeem->Stamp_Fee = $request->stamp_fee;
        $NewRedeem->Document_Charges = $request->document_charges;
        $NewRedeem->Advance_Balance = $request->advance_balance;
        $NewRedeem->Discount = $request->redeem_discount;
        $NewRedeem->Payable_Total = $request->payable_total;
        $NewRedeem->OC = $request->oc;
        $NewRedeem->BC = $request->bc;
        $NewRedeem->save();

        return back()->with('done','The Redeem has been added');

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
