<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\Userrole;
use Datatables;
 
class UserRoleReportController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Userrole::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        

        return view('Userrole');
    }
 
    public function role_store(Request $request)
    {  
  
        $DepartmentId = $request->id;
  
        $Department   =   Userrole::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'role_code' => $request->role_code, 
                    'role_name' => $request->role_name,
                    'BC' => $request->BC, 
                    'OC' => $request->OC,
                    ]);    
                          
        return Response()->json($Department);
    }
 
    public function role_edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = Userrole::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function role_delete(Request $request)
    {
        $Department = Userrole::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }


}