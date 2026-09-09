<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobSheet;

class AllJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $jobsDetails = JobSheet::all();
        $jobsDetails = JobSheet::latest()->paginate(8);
        return view('RepairAllJobs')->with("alljobs" , $jobsDetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }



     // ............update job ajax.................
     public function update(Request $request){
        // $request->validate([
        //     'up_code'=>'required | max:10 | unique:customers,name,'.$request->up_id,
        //     'up_first_name'=>'required | max:20 ',
        //     'up_last_name'=>' max:20 ',
        //     'up_address1'=>'  max:40 ',
        //     'up_address2'=>'max:40 ',
        //     'up_contact1'=>['required','max:10', 'regex:/^0\d{9,}$/'],
        //     'up_contact2'=>['max:10'],
        //     'up_email'=>' max:30 ',
        //     'up_nic'=>'max:15',
        //     'up_driving_license'=>'max:15 ',
        //     'up_passport'=>'max:15 ',
        //     'up_other_identifications'=>'max:15 ',
        // ]);



        JobSheet::where('id',$request->up_id)->update([
            'Customer_Name'=>$request->up_customer_name,
            'Customer_Phone'=>$request->up_customer_phone,
            'Job_no'=>$request->up_job_no,
            'Date'=>$request->up_receipt_date,
            'Brand'=>$request->up_brand,
            'Device_Model'=>$request->up_device_model,
            'IMEI_Number'=>$request->up_imei_number,
            'Amount'=>$request->up_amount,
            'Advance'=>$request->up_advance,
            'Balance'=>$request->up_balance,
            'Password'=>$request->up_password,
            'Product_Configuration'=>$request->up_product_configuration,
            'Problem_Reported'=>$request->up_problem_reported,
            'Technician'=>$request->up_technician,
            'Status'=>$request->up_status,
            'OC'=>$request->up_oc,
            'BC'=>$request->up_bc,
            'Item' => $request->itemValues, // Directly set the comma-separated string
            'Problem' => $request->problemValues, // Directly set the comma-separated string

            // $jobSheet->Item = implode(', ', $request->input('items')),
            // $jobSheet->Problem = implode(', ', $request->input('problems'))

        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

 // delete job ajax
    public function destroy(Request $request)
    {
        JobSheet::find($request->job_id)->delete();
        return response()->json([
            'status'=>'success',
        ]);
    }

}
