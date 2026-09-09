<?php

namespace App\Http\Controllers;
use App\Models\branchDel;
use App\Models\MSchema;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(MSchema::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = MSchema::orderBy('code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        $branch = MSchema::all();
        return view('SchemaType')
        ->with("maxCustomer", $maxCustomerNos)
        ->with("DepartmentData", $branch);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSchemaType(Request $request)
    {

        $DepartmentId = $request->id;

        $Department   =   MSchema::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'code' => $request->code,
                    'SchemaType' => $request->SchemaType,
                    'InRate' => $request->InRate,
                    'DocumentCharage' => $request->DocumentCharage,
                    'PanaltyCharage' => $request->PanaltyCharage,
                    'DownPayment' => $request->DownPayment,
                    'BC' => $request->BC,
                    'OC' => $request->OC,
                    ]);

        return Response()->json($Department);
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
    public function updateSchemaType(Request $request)
    {
        $where = array('id' => $request->id);
        $Department  = MSchema::where($where)->first();

        return Response()->json($Department);
    }


    public function deleteSchema(Request $request)
    {
        $Department = MSchema::where('id',$request->id)->delete();

        return Response()->json($Department);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
