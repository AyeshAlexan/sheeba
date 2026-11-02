<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\MRoute;
use App\Models\TAdvancCusPayment;
use App\Models\TCusSaleTrance;
use App\Models\Company;
use App\Models\branchDel;

class CustomerController extends Controller
{
    public function index()
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $data = Customer::where('BC', $branch_code)->get();
        $this->attachLiveBalances($data);

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
            'credit_limit'=>'nullable | numeric | min:0',
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
        $customer->credit_limit=$request->credit_limit;
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
            'credit_limit'=>'nullable | numeric | min:0',
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
        $customer->credit_limit=$request->credit_limit;
        $customer->BC = auth()->user()->BC;
        $customer->OC = auth()->user()->username;
        $customer->save();

        $this->syncCashBalanceLedger($customer->Code, $request->other_identifications);

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
            'up_code'=>'required | max:40 | unique:customers,Code,'.$request->up_id,
            'up_first_name'=>'required | max:200 ',
            'up_last_name'=>' max:200 ',
            'up_address1'=>'  max:200 ',
            'up_address2'=>'max:200 ',
            'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
            'up_contact2'=>['max:10'],
            'up_email'=>' max:255 ',
            'up_nic'=>'max:15',
            'up_driving_license'=>'max:15 ',
            'up_passport'=>'max:15 ',
            'up_other_identifications'=>'max:15 ',
            'up_credit_limit'=>'nullable | numeric | min:0',
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
            'credit_limit'=>$request->up_credit_limit,
        ]);

        $this->syncCashBalanceLedger($request->up_code, $request->up_other_identifications);

        return response()->json([
            'status'=>'success',
        ]);
    }

    // The "Customer Cash Balance" field on Add/Edit Customer (Other_identifications)
    // is a quick shortcut for setting an opening balance without going to the
    // dedicated Customer Opening Balance screen. Keep it in sync with the ledger
    // (t_cus_sale_trances) so it actually contributes to the live balance shown
    // everywhere else, instead of being a disconnected static number.
    private function syncCashBalanceLedger($customerCode, $amount)
    {
        $amount = (float) ($amount ?? 0);
        $transNo = 'CUSBAL-' . $customerCode;

        TCusSaleTrance::updateOrCreate(
            [
                'trance_no'   => $transNo,
                'trance_type' => 'CUS_OPEN_BC',
                'customer'    => $customerCode,
            ],
            [
                'no'            => $transNo,
                'dr_trnce_code' => 'CUS_OPEN_BC',
                'dr_trnce_no'   => $transNo,
                'cr_amount'     => $amount,
                'cr_trnce_code' => 'CUS_OPEN_BC',
                'cr_trnce_no'   => $transNo,
                'dr_amount'     => 0,
                'Display_Ref'   => $transNo,
                'dDate'         => now()->toDateString(),
                'bc'            => auth()->user()->BC,
                'oc'            => auth()->user()->username,
            ]
        );
    }

    // Live running balance per customer, same cr-dr derivation used by the
    // Sales Invoice lookup and Customer Balance Report — Other_identifications
    // is a stale, manually-set field that doesn't move with sales/payments.
    private function attachLiveBalances($customers)
    {
        $balances = TCusSaleTrance::select('customer')
            ->selectRaw('SUM(cr_amount) - SUM(dr_amount) as balance')
            ->groupBy('customer')
            ->pluck('balance', 'customer');

        $customers->each(function ($customer) use ($balances) {
            $customer->LiveBalance = $balances[$customer->Code] ?? 0;
        });
    }

    // ............ customer pagination using ajax.................
    public function pagination(Request $request){
        $branch_code = auth()->user()->BC;
        $data = Customer::where('BC', $branch_code)->latest()->paginate(5);
        $this->attachLiveBalances($data);
        return view('customer_457pagination')->with("customers",$data)->render();
    }

    // ............search using ajax.................
    public function search(Request $request){
        $branch_code = auth()->user()->BC;
        $term = $request->search_string;

        $data = Customer::where('BC', $branch_code)
        ->where(function ($q) use ($term) {
            $q->where('Code', 'like', '%'.$term.'%')
            ->orWhere('First_name','like','%'.$term.'%')
            ->orWhere('Middle_name','like','%'.$term.'%')
            ->orWhere('Last_name','like','%'.$term.'%')
            ->orWhere('Address_1','like','%'.$term.'%')
            ->orWhere('City_1','like','%'.$term.'%')
            ->orWhere('Address_2','like','%'.$term.'%')
            ->orWhere('City_2','like','%'.$term.'%')
            ->orWhere('Contact_1','like','%'.$term.'%')
            ->orWhere('Contact_2','like','%'.$term.'%')
            ->orWhere('Email','like','%'.$term.'%')
            ->orWhere('NIC','like','%'.$term.'%')
            ->orWhere('Driving_license','like','%'.$term.'%')
            ->orWhere('Passport','like','%'.$term.'%')
            ->orWhere('Other_identifications','like','%'.$term.'%');
        })
        ->orderBy('Code','desc')
        ->paginate(5);

        if($data->count() >= 1){
            $this->attachLiveBalances($data);
            return view('customer_457pagination')->with("customers",$data)->render();
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

        $customers = Customer::all();
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        return view('reports.customer_details_report')
        ->with("fromDate", $fromDate)
        ->with("toDate", $toDate)
        ->with("customers", $customers)
        ->with("companyData", Company::latest()->first())
        ->with("branchDel", branchDel::where('bccode', $branch_code)->first());
    }

    public function printCustomerDetailsReport(Request $request)
    {
        $branch_code = auth()->user()->BC;

        return view('reports.print.customer-details', [
            'customers'   => Customer::all(),
            'fromDate'    => $request->input('from_date'),
            'toDate'      => $request->input('to_date'),
            'companyData' => Company::latest()->first(),
            'branchDel'   => branchDel::where('bccode', $branch_code)->first(),
        ]);
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