<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\MBrand;
use App\Models\Workers;
use Datatables;
 
class MBrandController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(MBrand::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = MBrand::orderBy('Brand_code', 'desc')->value('Brand_code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        return view('MBrand')
        ->with("maxCustomer", $maxCustomerNos);
    }
 
    public function store_Brand(Request $request)
    {  
  
        $DepartmentId = $request->id;
  
        $Department   =   MBrand::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'Brand_code' => $request->Brand_code, 
                    'Brand_name' => $request->Brand_name,
                    'Branch' => $request->Branch, 
                    'BranchCode' => $request->BranchCode,
                
                    ]);    
                          
        return Response()->json($Department);
    }
 
    public function edit_Brand(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = MBrand::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function destroy_Brand(Request $request)
    {
        $Department = MBrand::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }

  
}