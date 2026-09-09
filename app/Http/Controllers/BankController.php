<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BankDetails;
use Datatables;

class BankController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(BankDetails::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('BankDeltails');
    }

    public function Bank_store(Request $request)
    {
        $DepartmentId = $request->id;

        $Department   =   BankDetails::updateOrCreate(
                    [
                     'id' => $DepartmentId
                    ],
                    [
                    'code' => $request->code,
                    'description' => $request->description,
                    'OC'=> $request->OC,
                    'BC'=> $request->BC,
                    ]);

        return Response()->json($Department);
    }

    public function Bank_edit(Request $request)
    {
        $where = array('id' => $request->id);
        $Department  = BankDetails::where($where)->first();

        return Response()->json($Department);
    }


    public function Bank_delete(Request $request)
    {
        $Department = BankDetails::where('id',$request->id)->delete();

        return Response()->json($Department);
    }
}
