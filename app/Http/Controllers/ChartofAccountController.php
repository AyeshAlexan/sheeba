<?php

namespace App\Http\Controllers;
use App\Models\MChartofAccount;
use App\Models\MMainAccountType;
use App\Models\MMainCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartofAccountController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(MChartofAccount::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $AccountType = MMainAccountType::all();
        $MainCategory = MMainCategory::all();
        return view('chartofaccount')
        ->with("AccountTypeData" , $AccountType)
        ->with("MainCategoryData" , $MainCategory);
    }


    public function ChartofAccountStore(Request $request)
    {   
        // {{-- 'account','accountsub','code','description',
            // 'opening_balance','controlaccount','bankaccount', 'BC','OC' --}}
  
        $ItemId = $request->id;

        $request->validate([
            'account'         => 'required',
            'accountsub'      => 'required',
            'code'            => ['required', Rule::unique('m_chartof_accounts', 'code')->ignore($ItemId)],
            'description'     => 'required',
            'opening_balance' => 'nullable|numeric',
        ], [
            'code.unique' => 'This account code is already in use — each account must have a unique code.',
        ]);

        $Item  =   MChartofAccount::updateOrCreate(
                    [
                     'id' => $ItemId
                    ],
                    [
                    'account' => $request->account, 
                    'accountsub'  => $request->accountsub,
                    'code' => $request->code,
                    'Bar_code'=> $request->Bar_code,
                    'description' => $request->description,
                    'opening_balance' => $request->opening_balance,
                    'controlaccount' => $request->controlaccount,
                    'bankaccount' => $request->bankaccount,
                    'BC' => $request->BC, 
                    'OC' => $request->OC,
                    ]);  
            return response()->json('Image uploaded successfully');
    }
 
    public function ChartofAccountEdit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Item  = MChartofAccount::where($where)->first();
       
        return Response()->json($Item);
    }
 
    public function ChartofAccountDelete(Request $request)
    {
        $Item = MChartofAccount::where('id',$request->id)->delete();
       
        return Response()->json($Item);
    }

}
