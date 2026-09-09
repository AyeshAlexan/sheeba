<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetWeightController extends Controller
{
    public function index()
    {
        header("Access-Control-Allow-Origin: *");
        $data = '';
        $myFileName = public_path("weight.txt");
        $myfile = fopen($myFileName, "r") or die("Unable to open file!");
        if(filesize($myFileName) > 0){
            $data = fread($myfile,filesize($myFileName));
        }
        echo $data;
        fclose($myfile);
    }
}
