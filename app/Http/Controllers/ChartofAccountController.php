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
        $AccountType = MMainAccountType::all();
        $MainCategory = MMainCategory::all();

        $accounts = MChartofAccount::orderBy('accountsub')
            ->orderBy('account')
            ->orderBy('code')
            ->get();

        // A code shared by more than one account is a structural error —
        // flag it in the tree so it's impossible to miss.
        $duplicateCodes = $accounts->countBy('code')
            ->filter(fn ($count) => $count > 1)
            ->keys();

        // Type → Group → Accounts, matching how a chart of accounts is
        // actually organized (not a flat list).
        $tree = $accounts->groupBy(fn ($a) => $a->accountsub ?: 'Unclassified')
            ->map(fn ($typeAccounts) => $typeAccounts->groupBy(fn ($a) => $a->account ?: 'Ungrouped'));

        return view('chartofaccount')
        ->with("AccountTypeData" , $AccountType)
        ->with("MainCategoryData" , $MainCategory)
        ->with("tree", $tree)
        ->with("duplicateCodes", $duplicateCodes);
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
        ], [
            'account.required'    => 'Account Group is required.',
            'accountsub.required' => 'Account Type is required.',
            'code.unique'         => 'This account code is already in use — each account must have a unique code.',
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
                    'controlaccount' => $request->controlaccount,
                    'bankaccount' => $request->bankaccount,
                    'BC' => $request->BC,
                    'OC' => $request->OC,
                    ]);
            return response()->json('Chart of account saved successfully');
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
