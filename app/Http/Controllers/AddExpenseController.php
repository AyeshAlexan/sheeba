<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TExpense;

class AddExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maxCustomerNo = TExpense::orderBy('Expense_no', 'desc')->value('Expense_no');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);
        return view('AddExpense')
        ->with("maxCustomer", $maxCustomerNos);
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
    public function storeExpense(Request $request)
    {
        {
            $post = new TExpense;
            $post->Expense_no = $request->Expense_no;
            $post->Expense_date = $request->Expense_date;
            $post->ExpenseType = $request->ExpenseType;
            $post->Expense_note = $request->Expense_note;
            $post->Expense_Amount = $request->Expense_Amount;
            $post->save();
            return redirect('AddExpense')->with('status', 'Expense Form Data Has Been inserted');
        }
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
