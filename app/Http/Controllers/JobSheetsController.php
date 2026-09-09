<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\JobSheet;
use App\Models\Company;

class JobSheetsController extends Controller
{

    public function index()
    {
        // get max job number max
        $maxJobSheetNo = JobSheet::orderBy('Job_no', 'desc')->value('Job_no');
        $maxJobNo = str_pad($maxJobSheetNo, 4, '0', STR_PAD_LEFT);
        // get max Customer number code
        $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('JobSheet')
        ->with("maxCustomer", $maxCustomerNos)
        ->with("maxJobSheetNo", $maxJobNo);
    }


    public function create(Request $request)
    {

    }


    public function store(Request $request)
    {
        $jobSheet = new JobSheet();
        $jobSheet->Customer_Name=$request->customer_name;
        $jobSheet->Customer_Phone=$request->customer_phone;
        $jobSheet->Job_no=$request->job_no;
        $jobSheet->Date=$request->receipt_date;
        $jobSheet->Brand=$request->brand;
        $jobSheet->Device_Model=$request->device_model;
        $jobSheet->IMEI_Number=$request->imei_number;
        $jobSheet->Amount=$request->amount;
        $jobSheet->Advance=$request->advance;
        $jobSheet->Balance=$request->balance;
        $jobSheet->Serial_Number=$request->serial_no;
        $jobSheet->Password=$request->password;
        $jobSheet->Product_Configuration=$request->product_configuration;
        $jobSheet->Problem_Reported=$request->problem_reported;
        $jobSheet->Product_Condition=$request->product_condition;
        $jobSheet->Technician=$request->technician;
        $jobSheet->Status='Pending';
        $jobSheet->OC=$request->operator;
        $jobSheet->BC=$request->branch;
        $jobSheet->Item = implode(', ', $request->input('items'));
        $jobSheet->Problem = implode(', ', $request->input('problems'));
        $jobSheet->save();



        $job_no = $request->job_no;
        $jobSheetData = JobSheet::where('Job_no', $job_no)->get();

         $companyData = Company::latest()->paginate(1);

        // Generate the PDF content using a view
        $pdf = PDF::loadView('jobSheetPrint', ['jobSheetData' => $jobSheetData, 'companyData' => $companyData]);


        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/receipt.pdf');
        // $pdfPath = storage_path('../pdf/receipt.pdf');
        $pdf->save($pdfPath);

        $pdfUrl = asset('public/assets/pdf/receipt.pdf');

        // $pdfUrl = attach('pdf/receipt.pdf');
        // Return the PDF as a download
        // return $pdf->stream();

        return back()
        ->with('done','The Job Sheet has been added..!')
        ->with("pdfLink", $pdfUrl);

    }


    public function show($id)
    {

    }


    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {

    }
}
