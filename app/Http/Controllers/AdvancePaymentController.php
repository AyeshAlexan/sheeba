<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TAdvancCusPayment;
use App\Models\Customer;
use App\Models\TAccountTrans;
use App\Models\MChartofAccount;
use App\Models\Company;
use Datatables;
use PDF;

class AdvancePaymentController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(TAdvancCusPayment::where('BC', auth()->user()->BC)->select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = TAdvancCusPayment::orderBy('invoice_no', 'desc')->value('invoice_no');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);


        $customerData = Customer::all();
        return view('sales_customer_payment')
        ->with("maxCustomer", $maxCustomerNos)
        ->with("customer", $customerData);
    }

    public function addcustomerPayment(Request $request)
    {

        $DepartmentId = $request->id;

        $Department   =   TAdvancCusPayment::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'invoice_no' => $request->invoice_no,
                    'date' => $request->date,
                    'customer_name' => $request->customer_name,
                    'cus_code' => $request->cus_code,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'OC' => $request->OC,
                    'BC' => $request->BC,
                    ]);


        $AccountTrans = new TAccountTrans;
        $AccountTrans->trance_type="ADVANCPAYMENT";
        $AccountTrans->Ddate = $request->date;
        $AccountTrans->dr_amount = $request->amount;
        $AccountTrans->AccCode = $request->cus_code;
        $AccountTrans->Description= $request->description;
        $AccountTrans->cr_amount = "0";
        $AccountTrans->trance_no =  $request->invoice_no;
        $AccountTrans->no =  $request->invoice_no;
        $AccountTrans->BC = auth()->user()->BC;
        $AccountTrans->OC = auth()->user()->username;
        $AccountTrans->save();


        $companyData = Company::latest()->paginate(1);
        $branch_code = auth()->user()->BC;

        $advance_payment = TAdvancCusPayment::where('invoice_no', $request->invoice_no,)
                        ->where('BC', $branch_code)
                        ->get();

        $advance_payment_cus_data = Customer::where('Code', $request->cus_code,)
                                    ->get();

        $pdf = PDF::loadView('advancePaymentInvoicePrint', [
            'advance_payment' => $advance_payment,
            'advance_payment_cus_data' => $advance_payment_cus_data,
            'companyData' => $companyData
            ]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Advance_Payment_Invoice.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('public/assets/pdf/Advance_Payment_Invoice.pdf');


        return Response()->json($Department);
    }

    public function UpdatecustomerPayment(Request $request)
    {
        $where = array('id' => $request->id);
        $Department  = TAdvancCusPayment::where($where)->first();

        return Response()->json($Department);
    }


    public function DeletecustomerPayment(Request $request)
    {
        $trance_type = 'ADVANCPAYMENT';
        $Department = TAdvancCusPayment::where('id',$request->id)->delete();
        $Department = TAccountTrans::where('trance_type',$trance_type)
                                    ->where('trance_no',$request->invoice_no)
                                    ->delete();
        return Response()->json($Department);
    }

    public function CustomerSearch(Request $request){
        // Retrieve the 'amount' parameter from the request
        $code = $request->category;

        // Query the 'Customer' model to get data based on 'First_name'
        $data = Customer::where('First_name', $code)->get();

        // Return a JSON response with the retrieved data
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }




}

