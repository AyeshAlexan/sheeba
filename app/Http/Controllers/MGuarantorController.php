<?php
namespace App\Http\Controllers;
use App\Models\MGuarantor;
use Illuminate\Http\Request;

class MGuarantorController extends Controller
{
    public function index()
    {
        $data = MGuarantor::latest()->get();

         // get max Customer number code
         $maxSupplierNo = MGuarantor::orderBy('Code', 'desc')->value('Code');
         $maxSupplierNos = str_pad($maxSupplierNo, 4, '0', STR_PAD_LEFT);

        return view("MGuarantor")
        ->with("maxSupplier", $maxSupplierNos)
        ->with("Suppliers" , $data);
    }


    public function get(Request $request)
    {
        $code =$request->search_string;
        $data = MGuarantor::where('Code', $code)->get();
        // $data = Customer::where('NIC', 'like', $request->search_string.'%');
        if($data->count()!=null){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    public function getGuarantor1Data(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $guarantorCode = $request->search_string;
        $data = MGuarantor::where('Code',$guarantorCode)->get();

        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    public function getGuarantor2Data(Request $request){
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $guarantorCode = $request->search_string;
        $data = MGuarantor::where('Code',$guarantorCode)->get();

        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    // public function addCustomer(Request $request)
    // {
    //     $request->validate([
    //         'code'=>'required | max:10 | unique:suppliers',
    //         'name'=>'required | max:200 ',
    //         'address1'=>' max:400 ',
    //         'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
    //         'email'=>' email | max:80 ',

    //     ]);

    //     $customer = new Suppliers();
    //     $customer->Code=$request->code;
    //     $customer->Name=$request->name;
    //     $customer->Address_1=$request->address1;
    //     $customer->Contact_1=$request->contact1;
    //     $customer->Email=$request->email;
    //     $customer->Status=$request->blacklisted;
    //     $customer->save();
    //     return back()->with('added','The Customer has been added..!');
    // }

    //  create customer ajax
    public function create(Request $request){
        $request->validate([
            'code'=>'required | max:10 ',
            'name'=>'required | max:200 ',
            'address1'=>' max:400 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'email'=>' email | max:80 ',
        ]);

        $customer = new MGuarantor();
        $customer->Code=$request->code;
        $customer->Name=$request->name;
        $customer->Address_1=$request->address1;
        $customer->Contact_1=$request->contact1;
        $customer->Email=$request->email;
        $customer->Status=$request->status;
        $customer->save();
        return response()->json([
            'status'=>'success',
        ]);
    }

    // delete customer ajax
    public function delete(Request $request){
        MGuarantor::find($request->customer_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

    // ............update using ajax.................
    public function update(Request $request){
        $request->validate([
            'up_code'=>'required | max:4 ,name,'.$request->up_id,
            'up_name'=>'required | max:20 ',
            'up_address1'=>'required | max:40 ',
            'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'up_email'=>'required | email | max:30 ',
        ]);

        MGuarantor::where('id',$request->up_id)->update([
            'Code'=>$request->up_code,
            'Name'=>$request->up_name,
            'Address_1'=>$request->up_address1,
            'Contact_1'=>$request->up_contact1,
            'Email'=>$request->up_email,
            'Status'=>$request->up_status,
        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

    // ............ customer pagination using ajax.................
    public function pagination(Request $request){
        $data = MGuarantor::latest()->paginate(5);
        return view('customer_pagination')->with("customers",$data)->render();
    }

    // ............search using ajax.................
    public function search(Request $request){
        $data = MGuarantor::where('Code', 'like', '%'.$request->search_string.'%')
        ->orWhere('Name','like','%'.$request->search_string.'%')
        ->orWhere('Address_1','like','%'.$request->search_string.'%')
        ->orWhere('Address_2','like','%'.$request->search_string.'%')
        ->orWhere('Contact_1','like','%'.$request->search_string.'%')
        ->orWhere('Contact_2','like','%'.$request->search_string.'%')
        ->orWhere('Email','like','%'.$request->search_string.'%')
        ->orWhere('NIC','like','%'.$request->search_string.'%')
        ->orWhere('Driving_license','like','%'.$request->search_string.'%')
        ->orWhere('Passport','like','%'.$request->search_string.'%')
        ->orWhere('Other_identifications','like','%'.$request->search_string.'%')
        ->orderBy('Code','desc')
        ->paginate(5);

        if($data->count() >= 1){
            return view('customer_pagination')->with("customers",$data)->render();
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

    public function getByID(Request $request)
    {
        $code= $request->code;
        $Cusdata = MGuarantor::where('Code', $code)->get();
        // dd($Cusdata);
        return redirect('pawning')->with('getByID', $Cusdata);
    }

    public function show($id)
    { }

    public function edit($id)
    {   }



    public function destroy($Code)
    {
        $data = MGuarantor::where('Code', $Code)->delete();
        return redirect()->back()->with("delete", "Receipt deleted");
    }
}
