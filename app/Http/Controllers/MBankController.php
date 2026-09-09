<?php
namespace App\Http\Controllers;
use App\Models\MBank;
use Illuminate\Http\Request;

class MBankController extends Controller
{


    public function index()
    {
        return view('MBank');
    }


    function MBank_add(Request $request){
        $insert = [
            'code' => $request->code,
            'bankname'=> $request->bankname,
        ];

        $add = MBank::create($insert);
        if($add){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record created succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record created failed!'
            ];
            return $response;
        }
    } 

    function MBank_view(Request $request){
        return MBank::find($request->id);
    } 

    function MBank_delete(Request $request){
        $delete =  MBank::destroy($request->id);
        if($delete){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record deleted succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record deleted failed!'
            ];
            return $response;
        }
    } 

    function MBank_edit(Request $request){
        $update = [
            'code' => $request->code,
            'bankname'=> $request->bankname,
        ];
        $edit = MBank::where('id', $request->MBank_id)->update($update);
        if($edit){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record updated succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record updated failed!'
            ];
            return $response;
        }
    } 

    function MBank_list(){
        return MBank::all();
    }
} 