<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\itemSetup;
use Datatables;

class itemSetupController extends Controller
{
    public function index()
    {
        $items = itemSetup::latest()->paginate(5);
        return view('item_setup')->with("item" , $items);

    }

    // ------------- Create Item using Ajex-------------
    public function create(Request $request){
        $request->validate([
            'code'=>'required | max:5 ',
            'description'=>'required | max:25 ',
            'category'=>'required | max:10',
            'sub_category'=>'required | max:10',
        ]);
        $item = new itemSetup;
        $item->code = $request->code;
        $item->ItemDesc = $request->description;
        $item->Category = $request->category;
        $item->subcategory = $request->sub_category;
        $item->save();
        return response()->json([
            'status'=>'success',
        ]);
    }

       // ------------- delete item using Ajex-------------
       public function delete(Request $request)
       {
        itemSetup::find($request->item_id)->delete();
           return response()->json([
               'status'=>'success',
           ]);
       }

    public function update(Request $request)
    {
        $request->validate([
            'up_code'=>'required | max:5 ',
            'up_description'=>'required | max:25 ',
            'up_category'=>'required | max:10',
            'up_sub_category'=>'required | max:10',
        ]);

        itemSetup::where('id',$request->up_id)->update([
            'code'=>$request->up_code,
            'ItemDesc'=>$request->up_description,
            'Category'=>$request->up_category,
            'subcategory'=>$request->up_sub_category,
        ]);

        return response()->json([
            'status'=>'success',
        ]);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $itemsetup  = itemSetup::where($where)->first();

        return Response()->json($itemsetup);
    }

    public function destroy(Request $request)
    {
        $itemsetup = itemSetup::where('id',$request->id)->delete();

        return Response()->json($itemsetup);
    }
}
