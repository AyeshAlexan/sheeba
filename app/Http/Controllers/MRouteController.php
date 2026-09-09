<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MRoute;
use App\Models\branchDel;

class MRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        // $Auth = Auth::user()->Branch;
        // $branchCode = Department::where('Branch', 002)->get();

        $branchCode =MRoute::all();
        $emp =MRoute::paginate(5);
        $branch =branchDel::all();
        // $Department = Department::all();

        
        $maxCustomerNo = MRoute::orderBy('code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('Route',['DepartmentData' => $emp])
        ->with("maxCustomer", $maxCustomerNos)
        ->with("DepartmentData" , $branchCode)
        ->with("Branch" , $branch);
    }

// .....................Add Branch using Ajax.........................
public function AddRoute(Request $request)
{
    $request->validate([
        'code'=>'required',
        'description'=>'required | max:20',
        'Branch'=>'required | max:40 ',
        'BranchCode'=>'required | max:10 ',
    ]);

    $branch = new MRoute;
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
    public function UpdateRoute(Request $request){
        $request->validate(
        [
            'up_code'=>'required | max:5 ',
            'up_description'=>'required | max:20,',
            // 'up_Branch'=>'required | max:40 ',
            // 'up_BranchCode'=>'required | max:40 ',
        ]);

        MRoute::where('id',$request->up_id)->update([
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
    public function DeleteRoute(Request $request){
        MRoute::find($request->Department_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
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
