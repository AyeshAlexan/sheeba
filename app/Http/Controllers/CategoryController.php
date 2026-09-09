<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Workers;
use Datatables;

class CategoryController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Category::select('*'))
            ->addColumn('action', 'Action_button')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $maxCustomerNo = Category::orderBy('code', 'desc')->value('code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        return view('Category')
        ->with("maxCustomer", $maxCustomerNos);
    }

public function store(Request $request)
{
    $request->validate([
        'code' => 'required',
        'description' => 'required',
        'Cate_code' => 'required',
    ]);

    Category::updateOrCreate(
        ['id' => $request->id],
        [
            'code' => $request->code,
            'description' => $request->description,
            'Cate_code' => $request->Cate_code,
        ]
    );

    return response()->json(['success' => true]);
}

    public function update(Request $request)
    {
        $where = array('id' => $request->id);
        $Department  = Category::where($where)->first();

        return Response()->json($Department);
    }


    public function destroy(Request $request)
    {
        $Department = Category::where('id',$request->id)->delete();

        return Response()->json($Department);
    }


}