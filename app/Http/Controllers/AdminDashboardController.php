<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('AdminDashboard');

    }

    public function echart(Request $request)
    {
    	$fruit = Product::where('product_type','fruit')->get();
    	$veg = Product::where('product_type','vegitable')->get();
    	$grains = Product::where('product_type','grains')->get();
    	$fruit_count = count($fruit);    	
    	$veg_count = count($veg);
    	$grains_count = count($grains);
    	return view('echart',compact('fruit_count','veg_count','grains_count'));
    }
}
