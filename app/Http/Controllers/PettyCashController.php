<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\TPettyCash;
use App\Models\MChartofAccount;
use App\Models\TAccountTrans;
use Datatables;
use Illuminate\Support\Facades\Auth;
 
// Amount

class PettyCashController extends Controller
{


    // public function index()
    // {
    //     if (request()->ajax()) {
    //         $user = auth()->user();
    
    //         $query = TPettyCash::where('BC', $user->BC);
    
    //         // Check if the user has the 'admin' role
    //         if ($user->role !== 'Admin') {
    //             // If not an admin, select only specific columns
    //             $query->select(['', '', /* Add other columns you want to select */]);
    //         }
    
    //         return datatables()->of($query)
    //             ->addColumn('action', 'Action_button')
    //             ->rawColumns(['action'])
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(TPettyCash::where('BC', auth()->user()->BC)->select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        
        $maxCustomerNo = TPettyCash::orderBy('invoice_no', 'desc')->value('invoice_no');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

    
        $ChartAccount = MChartofAccount::all();
        return view('PettyCash')
        ->with("maxCustomer", $maxCustomerNos)
        ->with("Amount", $ChartAccount);
    }
    
    
    
 
    public function addPettycash(Request $request)
    {

    
        $DepartmentId = $request->id;
    
        $Department = TPettyCash::updateOrCreate(
            [
                'id' => $DepartmentId,
            ],
            [
                'date' => $request->date,
                'invoice_no' => $request->invoice_no,
                'cramount' => $request->cramount,
                'crcode' => $request->crcode,
                'dramount' => $request->dramount,
                'drcode' => $request->drcode,
                'description' => $request->description,
                'amount' => $request->amount,
                'OC' => $request->OC,
                'BC' => $request->BC,
            ]
        );

        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type = $request->invoice_no;
        $AccountTrans->trance_type="PETTYCASH";
        $AccountTrans->Ddate = $request->date;
        $AccountTrans->dr_amount = $request->amount;
        $AccountTrans->AccCode = $request->drcode;
        $AccountTrans->cr_amount = "0";
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();



        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type = $request->invoice_no;
        $AccountTrans->trance_type="PETTYCASH";
        $AccountTrans->Ddate = $request->date;
        $AccountTrans->dr_amount="0";
        $AccountTrans->cr_amount = $request->amount;
        $AccountTrans->AccCode = $request->crcode;
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();
    
        return response()->json($Department);
    }
    
 
    public function UpdatePettycash(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = TPettyCash::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function DeletePettycash(Request $request)
    {
        $Department = TPettyCash::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }

   

}

