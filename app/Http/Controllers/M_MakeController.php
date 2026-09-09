<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\M_Make;
use Datatables;
 
class M_MakeController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(M_Make::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = M_Make::orderBy('Make_code', 'desc')->value('Make_code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        $make = M_Make::all();

        return view('M_Make')
        ->with("maxCustomer", $maxCustomerNos)
        -> with("Branch",$make );

    }
 
    public function store_Make(Request $request)
    {  
  
        $DepartmentId = $request->id;
  
        $Department   =   M_Make::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'Make_code' => $request->Make_code, 
                    'Make_name' => $request->Make_name,
                    'Branch' => $request->Branch, 
                    'BranchCode' => $request->BranchCode,
                    ]);    
                          
        return Response()->json($Department);
    }
 
    public function edit_Make(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = M_Make::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function destroy_Make(Request $request)
    {
        $Department = M_Make::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }

}

