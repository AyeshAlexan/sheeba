<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\MRoute;
use App\Models\TAdvancCusPayment;
use App\Models\TCusSaleTrance;

class CustomerController extends Controller
{
    public function index()
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $data = Customer::where('BC', $branch_code)->get();

        // 1. Plain/default sequence (e.g., 001, 002)
        $maxCustomerNo = Customer::where('BC', $branch_code)
            ->where('Code', 'REGEXP', '^[0-9]+$')
            ->orderByRaw('CAST(Code AS UNSIGNED) DESC')
            ->value('Code');
        $nextCustomerNo = $maxCustomerNo === null ? 1 : (int) $maxCustomerNo + 1;
        $maxCustomerNos = str_pad($nextCustomerNo, 3, '0', STR_PAD_LEFT);

        // 2. Retail sequence (e.g., R1, R2)
        $maxRetailNo = Customer::where('BC', $branch_code)
            ->where('Code', 'REGEXP', '^R[0-9]+$')
            ->orderByRaw("CAST(SUBSTRING(Code, 2) AS UNSIGNED) DESC")
            ->value('Code');
        $nextRetailNo = $maxRetailNo === null ? 1 : (int) substr($maxRetailNo, 1) + 1;
        $maxRetail = 'R' . $nextRetailNo;

        // 3. Wholesale sequence (e.g., 00001, 00002)
        $maxWholesaleNo = Customer::where('BC', $branch_code)
            ->where('Code', 'REGEXP', '^00[0-9]+$')
            ->orderByRaw("CAST(SUBSTRING(Code, 3) AS UNSIGNED) DESC")
            ->value('Code');
        $nextWholesaleNo = $maxWholesaleNo === null ? 1 : (int) substr($maxWholesaleNo, 2) + 1;
        $maxWholesale = '00' . str_pad($nextWholesaleNo, 3, '0', STR_PAD_LEFT);

        $route = MRoute::all();

        return view("customers")
            ->with("Route", $route)
            ->with("maxCustomer", $maxCustomerNos)
            ->with("maxRetail", $maxRetail)
            ->with("maxWholesale", $maxWholesale)
            ->with("customers", $data);
    }


    public function addCustomer(Request $request)
    {
        $request->validate([
            'code'=>'required | max:40 | unique:customers,Code',
            'name'=>'required | max:200 ',
            'address1'=>'required | max:200 ',
            'address2'=>'max:200 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'contact2'=>['max:10', 'regex:/^0\d{9,}$/'],
            'email'=>'required | email | max:200 ',
            'nic'=>'required | max:15 | unique:customers',
            'driving_license'=>'max:15 ',
            'passport'=>'max:15 ',
            'other_identifications'=>'max:100 ',
        ], [
            'code.unique' => 'This customer code is already in use — each customer must have a unique code.',
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
        $customer->BC = auth()->user()->BC;
        $customer->OC = auth()->user()->username;
        $customer->save();
        return back()->with('added','The Customer has been added..!');
    }

    //  create customer ajax
    public function create(Request $request){
        $request->validate([
            'code'=>'required | max:40 | unique:customers,Code',
            'first_name'=>'required | max:200 ',
            'address1'=>'max:200',
            'address2'=>'max:200 ',
            'contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'] ,
            'contact2'=>'max:10',
            'email'=>' max:200 ',
            'nic'=>'nullable | max:15 | unique:customers',
            'driving_license'=>'max:15 ',
            'passport'=>'max:15 ',
            'other_identifications'=>'max:15 ',
        ], [
            'code.unique' => 'This customer code is already in use — each customer must have a unique code.',
        ]);
        $customer = new Customer();
        $customer->Code=$request->code;
        $customer->Title=$request->title;
        $customer->Gender=$request->gender;
        $customer->Name=$request->name;
        $customer->First_name=$request->first_name;
        $customer->Middle_name=$request->middle_name;
        $customer->Last_name=$request->last_name;
        $customer->Address_1=$request->address1;
        $customer->City_1=$request->city1;
        $customer->Address_2=$request->address2;
        $customer->City_2=$request->city2;
        $customer->Contact_1=$request->contact1;
        $customer->Contact_2=$request->contact2;
        $customer->Email=$request->email;
        $customer->NIC=$request->nic;
        $customer->Driving_license=$request->driving_license;
        $customer->Passport=$request->passport;
        $customer->Other_identifications=$request->other_identifications;
        $customer->Status=$request->status;
        $customer->BC = auth()->user()->BC;
        $customer->OC = auth()->user()->username;
        $customer->save();


        $tel_no = $request->contact1;
        $CusData = Customer::where('Contact_1', $tel_no)->get();

        return response()->json([
            'status'=>'success',
            'telNo'=> $CusData,
        ]);
    }

    // delete customer ajax
    public function delete(Request $request){
        Customer::find($request->customer_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

    // ............update using ajax.................
    public function update(Request $request){
        $request->validate([
            'up_code'=>'required | max:40 | unique:customers,name,'.$request->up_id,
            'up_first_name'=>'required | max:200 ',
            'up_last_name'=>' max:200 ',
            'up_address1'=>'  max:200 ',
            'up_address2'=>'max:200 ',
            'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'up_contact2'=>['max:10'],
            'up_email'=>' max:30 ',
            'up_nic'=>'max:15',
            'up_driving_license'=>'max:15 ',
            'up_passport'=>'max:15 ',
            'up_other_identifications'=>'max:15 ',
        ]);

        Customer::where('id',$request->up_id)->update([
            'Code'=>$request->up_code,
            'Title'=>$request->up_title,
            'Gender'=>$request->up_gender,
            'First_name'=>$request->up_first_name,
            'Middle_name'=>$request->up_middle_name,
            'Last_name'=>$request->up_last_name,
            'Address_1'=>$request->up_address1,
            'City_1'=>$request->up_city1,
            'Address_2'=>$request->up_address2,
            'City_2'=>$request->up_city2,
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
        $data = Customer::latest()->paginate(5);
        return view('customer_pagination')->with("customers",$data)->render();
    }

    // ............search using ajax.................
    public function search(Request $request){
        $data = Customer::where('Code', 'like', '%'.$request->search_string.'%')
        ->orWhere('First_name','like','%'.$request->search_string.'%')
        ->orWhere('Middle_name','like','%'.$request->search_string.'%')
        ->orWhere('Last_name','like','%'.$request->search_string.'%')
        ->orWhere('Address_1','like','%'.$request->search_string.'%')
        ->orWhere('City_1','like','%'.$request->search_string.'%')
        ->orWhere('Address_2','like','%'.$request->search_string.'%')
        ->orWhere('City_2','like','%'.$request->search_string.'%')
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

   public function get(Request $request)
{
    $nic = $request->search_string;
    $data = Customer::where('Code', $nic)->get();

    if ($data->count() == 0) {
        return response()->json(['status' => 'not_found']);
    }

    // Fix: query TCusSaleTrance using the customer code directly
    $transactions = TCusSaleTrance::where('customer', $nic)->get();

    $dr_amount = $transactions->sum('dr_amount') ?? 0;
    $cr_amount = $transactions->sum('cr_amount') ?? 0;

    $advance = $cr_amount - $dr_amount;

    // Pass null if no advance balance (so blade @if works correctly)
    $advance = $advance > 0 ? $advance : null;

    return view('salesInvoiceGetCustomer')
        ->with('customer_get', $data)
        ->with('advance', $advance)
        ->render();
}
    
    public function getCustomerBalance(Request $request){
        $code =$request->cus_code;
        $data = TCusSaleTrance::where('customer', $code)->get();
        $dr_amount = 0;
        $cr_amount = 0;

        foreach ($data as $record) {
            $dr_amount += $record->dr_amount ?? 0;
            $cr_amount += $record->cr_amount ?? 0;
        }

        $balance = $cr_amount - $dr_amount;

        return response()->json([
            'status'=>'success',
            'balance'=> $balance,
        ]);
    }




    public function getByID(Request $request)
    {
        $code= $request->code;
        $Cusdata = Customer::where('Code', $code)->get();
        // dd($Cusdata);
        return redirect('pawning')->with('getByID', $Cusdata);
    }

    public function show($id)
    { }

    public function edit($id)
    {   }

    public function indexEdit($code){
        $Cusdata = Customer::where('Code', $code)->get();
        // dd($Cusdata);
        return view('editCustomers')->with('Cusdata', $Cusdata);
    }


    public function destroy($Code)
    {
        $data = Customer::where('Code', $Code)->delete();
        return redirect()->back()->with("delete", "Receipt deleted");
    }
    
    
       public function customerDetailsReportIndex(Request $request){

        $branch_code = auth()->user()->BC;
        $customers = Customer::all();

        $fromDate="";
        $toDate ="";

        return view('reports.customer_details_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("customers", $customers);
    }
    
    
           public function getReturnCustomer(Request $request)
{
    $nic = $request->search_string;
    $data = Customer::where('Code', $nic)->get();

    if ($data->count() == 0) {
        return response()->json(['status' => 'not_found']);
    }

    // Fix: query TCusSaleTrance using the customer code directly
    $transactions = TCusSaleTrance::where('customer', $nic)->get();

    $dr_amount = $transactions->sum('dr_amount') ?? 0;
    $cr_amount = $transactions->sum('cr_amount') ?? 0;

    $advance = $cr_amount - $dr_amount;

    // Pass null if no advance balance (so blade @if works correctly)
    $advance = $advance > 0 ? $advance : null;

    return view('salesReturnInvoiceGetCustomer')
        ->with('customer_get', $data)
        ->with('advance', $advance)
        ->render();
}
}