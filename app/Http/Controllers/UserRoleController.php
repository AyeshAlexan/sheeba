<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Userrole;
use App\Models\RolePermission;
use Datatables;

class UserRoleController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Userrole::select('*'))
            ->addColumn('permissions', function ($role) {
                $count = RolePermission::whereRaw('TRIM(role_name) = ?', [trim($role->role_name)])
                    ->where('is_enabled', true)
                    ->count();

                $url = route('role_permissions', ['role' => trim($role->role_name)]);

                if ($count > 0) {
                    return '<a href="' . $url . '" class="badge text-bg-success text-decoration-none">'
                        . $count . ' module' . ($count === 1 ? '' : 's') . ' &raquo;</a>';
                }

                return '<a href="' . $url . '" class="badge text-bg-secondary text-decoration-none">No access set &raquo;</a>';
            })
            ->addColumn('action', 'Action_button')
            ->rawColumns(['permissions', 'action'])
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