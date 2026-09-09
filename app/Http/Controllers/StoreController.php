<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Workers;
use Datatables;
 
class StoreController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Store::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        

        return view('Store');
    }
 
    public function store_Item(Request $request)
    {  
  
        $DepartmentId = $request->id;
  
        $Department   =   Store::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'Store_code' => $request->Store_code, 
                    'Store_name' => $request->Store_name,
                    'Branch' => $request->Branch, 
                    'BranchCode' => $request->BranchCode,
                    ]);    
                          
        return Response()->json($Department);
    }
 
    public function edit_store(Request $request)
    {   
        $where = array('id' => $request->id);
        $Department  = Store::where($where)->first();
       
        return Response()->json($Department);
    }
    
 
    public function destroy_store(Request $request)
    {
        $Department = Store::where('id',$request->id)->delete();
       
        return Response()->json($Department);
    }



    // public function get(Request $request)
    // {
    //     $nic =$request->search_string;
    //     $data = Store::where('Store_code', $nic)->get();
    //     // $data = Customer::where('NIC', 'like', $request->search_string.'%');
    //     if($data->count()!=null){
    //         // dd($data );
    //         return view('Store_search')->with("Store_get", $data)->render();
    //     }else{
    //         return response()->json([
    //             'status'=>'not_found'
    //         ]);
    //     }
    // }


    public function get(Request $request)
    {
        $nic =$request->search_string;
        $data = Workers::where('Worker_code', $nic)->get();
        // $data = Customer::where('NIC', 'like', $request->search_string.'%');
        if($data->count()!=null){
            // dd($data );
            return view('Works_search')->with("get_Workers", $data)->render();
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }
}