<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\branchDel;


class DepartmentController extends Controller
{
    


    public function index()
    {   

        // $Auth = Auth::user()->Branch;
        // $branchCode = Department::where('Branch', 002)->get();

        $UserBranchCode = Auth::user()->BC;
        $branchCode =Department::all();
        $branch =branchDel::all();

        $maxCustomerNo = Department::orderBy('code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
       
        $branch = branchDel::all();
        // $Department = Department::all();

        return view('Department')
        ->with("maxCustomer", $maxCustomerNos)
        ->with("DepartmentData" , $branchCode)
        ->with("Branch" , $branch);
    }

// .....................Add Branch using Ajax.........................
public function create(Request $request)
{
    $request->validate([
        'code'=>'required',
        'description'=>'required | max:20 ',
        'Branch'=>'required | max:40 ',
        'BranchCode'=>'required | max:10 ',
    ]);

    $branch = new Department;
    $branch->code = $request->code;
    $branch->description = $request->description;
    $branch->Branch = $request->Branch;
    $branch->BranchCode = $request->BranchCode;
    $branch->save();
    return response()->json([
        'status'=>'success',
    ]);
}

// ............update using ajax.................
    public function update(Request $request)
    {
        $request->validate(
        [
            'up_code'=>'required | max:5 ',
            'up_description'=>'required | max:20,',
            // 'up_Branch'=>'required | max:40 ',
            // 'up_BranchCode'=>'required | max:40 ',
        ]);

        Department::where('id',$request->up_id)->update([
            'code'=>$request->up_code,
            'description'=>$request->up_description,
            // 'Branch'=>$request->up_Branch,
            // 'BranchCode'=>$request->up_BranchCode,
        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

// ............delete using ajax.................
    public function delete(Request $request){
        Department::find($request->Department_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

    public function destroy($id)
    {
        $data = Department::find($id);
        $data->delete();
        return redirect()->back()->with("delete", "Department deleted");
    }

    // ............pagination using ajax.................
    // public function pagination(Request $request){
    //     $branchDel = Department::latest()->paginate(5);
    //     return view('branch_details_pagination')->with("branchDel",$branchDel)->render();
    // }


    // ............search using ajax.................
    // public function search(Request $request){
    //     $branchDel = Department::where('name', 'like', '%'.$request->search_string.'%')
    //     ->orWhere('bccode','like','%'.$request->search_string.'%')
    //     ->orWhere('address','like','%'.$request->search_string.'%')
    //     ->orWhere('contact1','like','%'.$request->search_string.'%')
    //     ->orWhere('contact2','like','%'.$request->search_string.'%')
    //     ->orWhere('date','like','%'.$request->search_string.'%')
    //     ->orderBy('bccode','desc')
    //     ->paginate(5);

    //     if($branchDel->count() >= 1){
    //         return view('branch_details_pagination')->with("branchDel",$branchDel)->render();
    //     }else{
    //         return response()->json([
    //             'status'=>'not_found'
    //         ]);
    //     }
    // }

    
    public function getUser(Request $request){
        $itemcode = $request -> category;
        $data = branchDel::where('name',$itemcode)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }
}
