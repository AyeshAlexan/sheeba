<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\TPaymentVoucher;
use App\Models\MChartofAccount;
use App\Models\TAccountTrans;
use Datatables;
 
// Amount

class PaymentVoucherController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(TPaymentVoucher::where('BC', auth()->user()->BC)->select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = TPaymentVoucher::orderBy('invoice_no', 'desc')->value('invoice_no');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);


        $ChartAccount = MChartofAccount::all();
        return view('PaymentVoucher')
        ->with("maxCustomer", $maxCustomerNos)
        -> with("Amount", $ChartAccount);
    }
 
    public function addPaymentVoucher(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ], [
            'description.required' => 'Please enter a description explaining this voucher.',
        ]);

        $DepartmentId = $request->id;

        $Department   =   TPaymentVoucher::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'invoice_no' => $request->invoice_no,       
                    'date' => $request->date,    
                    'cramount' => $request->cramount, 
                    'crcode' => $request->crcode, 
                    'dramount' => $request->dramount,
                    'drcode' => $request->drcode,
                    'description' => $request->description, 
                    'amount' => $request->amount,
                    'OC' => $request->OC, 
                    'BC' => $request->BC,
                    ]);  
                    
                    
        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type = $request->invoice_no;
        $AccountTrans->trance_type="VOUCHER";
        $AccountTrans->Ddate = $request->date;
        $AccountTrans->dr_amount = $request->amount;
        $AccountTrans->AccCode = $request->drcode;
        $AccountTrans->cr_amount = "0";
        $AccountTrans->Description = $request->description;
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();



        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type = $request->invoice_no;
        $AccountTrans->trance_type="VOUCHER";
        $AccountTrans->Ddate = $request->date;
        $AccountTrans->dr_amount="0";
        $AccountTrans->cr_amount = $request->amount;
        $AccountTrans->AccCode = $request->crcode;
        $AccountTrans->Description = $request->description;
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();
                          

                    	// [ 'cramount','dramount','description','amount','OC','BC'];
        return Response()->json($Department);
    }
 
    public function UpdatePaymentVoucher(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = TPaymentVoucher::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
public function DeletePaymentVoucher(Request $request)
{
    $Department = TPaymentVoucher::where('id', $request->id)->first();

    if ($Department) {
        // Delete related TAccountTrans records
        TAccountTrans::where('trance_no', $Department->invoice_no)
                     ->where('trance_type', 'VOUCHER')
                     ->where('BC', auth()->user()->BC)
                     ->delete();

        // Delete the payment voucher
        $Department->delete();
    }

    return Response()->json($Department);
}

    public function GetVoucher(Request $request){
        $Storecode = $request -> category;
        $data = MChartofAccount::where('description',$Storecode)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }

    public function GetDRVoucher(Request $request){
        $DRcode = $request -> amount;
        $data = MChartofAccount::where('description',$DRcode)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }

}