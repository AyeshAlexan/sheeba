<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Datatables;

class CompanyController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Company::select('*'))
            ->addColumn('action', 'Company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('Company');
    }

    public function Companystore(Request $request)
    {

        $CompanyId = $request->id;

        $Company   =   Company::updateOrCreate(
                    [
                     'id' => $CompanyId
                    ],
                    [
                    'co_code' => $request->co_code,
                    'name' => $request->name,
                    'address' => $request->address,
                    'co_number' => $request->co_number,
                    'one_number' => $request->one_number,
                    'two_number' => $request->two_number,
                    'fax_number' => $request->fax_number,
                    'email' => $request->email,
                    'Note' => $request->Note
                    ]);

        return Response()->json($Company);
    }

    public function Companyedit(Request $request)
    {
        $where = array('id' => $request->id);
        $Company  = Company::where($where)->first();

        return Response()->json($Company);
    }

    public function Companydestroy(Request $request)
    {
        $Company = Company::where('id',$request->id)->delete();

        return Response()->json($Company);
    }
}