<?php
namespace App\Http\Controllers;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index()
    {
        $data = Technician::latest()->paginate(5);
        return view("Technician")->with("Technician" , $data);
    }

    public function addCustomer(Request $request)
    {
        $request->validate([
            'code'=>'required | max:10 | unique:customers',
            'name'=>'required | max:40 ',
            'address1'=>' max:100 ',
            'address2'=>'max:100 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            
            'email'=>' email | max:50 ',
            'nic'=>' max:15 | unique:customers',
            'driving_license'=>'max:15 ',
            'passport'=>'max:15 ',
            'other_identifications'=>'max:15 ',
        ]);
        $customer = new Customer();
        $customer->Code=$request->code;
        $customer->Title=$request->title;
        $customer->Gender=$request->gender;
        $customer->Name=$request->name;
        $customer->Address_1=$request->address1;
        $customer->Address_2=$request->address2;
        $customer->Contact_1=$request->contact1;
        $customer->Contact_2=$request->contact2;
        $customer->Email=$request->email;
        $customer->NIC=$request->nic;
        $customer->Driving_license=$request->driving_license;
        $customer->Passport=$request->passport;
        $customer->Other_identifications=$request->other_identifications;
        $customer->Status=$request->blacklisted;
        $customer->save();
        return back()->with('added','The Customer has been added..!');
    }

    //  create customer ajax
    public function create(Request $request){
        $request->validate([
            'code'=>'required | max:10 | unique:customers',
            'name'=>'required | max:40 ',
            'address1'=>' max:100 ',
            'address2'=>'max:100 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            
            'email'=>' email | max:30 ',
            'nic'=>' max:15 | unique:customers',
            'driving_license'=>'max:15 ',
            'passport'=>'max:15 ',
            'other_identifications'=>'max:15 ',
        ]);
        $customer = new Technician();
        $customer->Code=$request->code;
        $customer->Title=$request->title;
        $customer->Gender=$request->gender;
        $customer->Name=$request->name;
        $customer->Address_1=$request->address1;
        $customer->Address_2=$request->address2;
        $customer->Contact_1=$request->contact1;
        $customer->Contact_2=$request->contact2;
        $customer->Email=$request->email;
        $customer->NIC=$request->nic;
        $customer->Driving_license=$request->driving_license;
        $customer->Passport=$request->passport;
        $customer->Other_identifications=$request->other_identifications;
        $customer->Status=$request->status;
        $customer->save();
        return response()->json([
            'status'=>'success',
        ]);
    }

    // delete customer ajax
    public function delete(Request $request){
        Technician::find($request->customer_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

    // ............update using ajax.................
    public function update(Request $request){
        $request->validate([
            'up_code'=>'required | max:10 | unique:customers,name,'.$request->up_id,
            'up_name'=>'required | max:40 ',
            'up_address1'=>' max:100 ',
            'up_address2'=>'max:100 ',
            'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            
            'up_email'=>' email | max:30 ',
            'up_nic'=>' max:15',
            'up_driving_license'=>'max:15 ',
            'up_passport'=>'max:15 ',
            'up_other_identifications'=>'max:15 ',
        ]);

        Technician::where('id',$request->up_id)->update([
            'Code'=>$request->up_code,
            'Title'=>$request->up_title,
            'Gender'=>$request->up_gender,
            'Name'=>$request->up_name,
            'Address_1'=>$request->up_address1,
            'Address_2'=>$request->up_address2,
            'Contact_1'=>$request->up_contact1,
            'Contact_2'=>$request->up_contact2,
            'Email'=>$request->up_email,
            'NIC'=>$request->up_nic,
            'Driving_license'=>$request->up_driving_license,
            'Passport'=>$request->up_passport,
            'Other_identifications'=>$request->up_other_identifications,
            'Status'=>$request->up_status,
        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

    // ............ customer pagination using ajax.................
    public function pagination(Request $request){
        $data = Technician::latest()->paginate(5);
        return view('customer_pagination')->with("customers",$data)->render();
    }

    // ............search using ajax.................
    public function search(Request $request){
        $data = Technician::where('Code', 'like', '%'.$request->search_string.'%')
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
        $Cusdata = Technician::where('Code', $code)->get();
        // dd($Cusdata);
        return redirect('pawning')->with('getByID', $Cusdata);
    }

    public function show($id)
    { }

    public function edit($id)
    {   }

    public function indexEdit($code){
        $Cusdata = Technician::where('Code', $code)->get();
        // dd($Cusdata);
        return view('editCustomers')->with('Cusdata', $Cusdata);
    }


    public function destroy($Code)
    {
        $data = Technician::where('Code', $Code)->delete();
        return redirect()->back()->with("delete", "Receipt deleted");
    }
}
