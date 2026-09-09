<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\MColor;
use App\Models\Workers;
use Datatables;
 
class MColorController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(MColor::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        $maxCustomerNo = MColor::orderBy('Color_code', 'desc')->value('Color_code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        return view('MColor')
        ->with("maxCustomer", $maxCustomerNos);
    }
 
    public function store_Color(Request $request)
    {  
  
        $DepartmentId = $request->id;
  
        $Department   =   MColor::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'Color_code' => $request->Color_code, 
                    'Color_name' => $request->Color_name,
                    'Branch' => $request->Branch, 
                    'BranchCode' => $request->BranchCode,
                    ]);    
                          
        return Response()->json($Department);
    }
 
    public function edit_Item(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = MColor::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function destroy_Item(Request $request)
    {
        $Department = MColor::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }

  
}