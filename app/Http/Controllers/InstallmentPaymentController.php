<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use App\Models\TInstalment;
use App\Models\Customer;
use App\Models\Company;
use App\Models\THirePurchaseSum;

class InstallmentPaymentController extends Controller
{

    public function index()
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        // get max invoice number max
        $maxInvoiceNo = TInstalment::orderBy('invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;
        return view('installmentPayment')
        ->with("maxInvoiceNo", $maxInvoice);
    }


    public function findInvoice(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $invoiceNo = $request->search_string;

        $hp_sumdata = THirePurchaseSum::where('invoice_no', $invoiceNo)
                    ->where('bc', $branch_code)
                    ->get();

        $data = TInstalment::where('invoice_no',$invoiceNo)
                ->where('bc', $branch_code)
                ->get();

        $inst_total = $data->sum('instalment_amount');
        // $install_total = number_format($inst_total,2);

        $amou_pay_total = $data->sum('amount_pay');
        // $amount_pay_total = number_format($amou_pay_total,2);

        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'hp_data' => $hp_sumdata,
                'install_total' => $inst_total,
                'amount_pay_total' => $amou_pay_total,
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }
    }

// TODO:
    public function create(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $request->validate([
            'invoice_no'=>'required',
            'invoice_date'=>'required',
            'instalment_invoice_no'=>'required',
            'paying_amount'=>'required',
        ]);

        $Invoice_no = $request->invoice_no;
        $Installment_date =  $request->invoice_date;

        $InstalmentData = TInstalment::where('invoice_no', $Invoice_no)
                        // ->where('instalment_date', $Installment_date)
                        ->where('amount_pay',0)
                        ->where('BC',$branch_code)
                        ->first();

        $row_date = $InstalmentData->instalment_date;
        $row_instalment = $InstalmentData->instalment_amount;

        if ($InstalmentData) {
            $amount_to_pay = $row_instalment;
            $amount_paying = $request->paying_amount;
            $difference = $amount_paying - $amount_to_pay;

            if($difference == 0){
                TInstalment::where('invoice_no', $Invoice_no)
                    ->where('instalment_date', $row_date)
                    ->where('BC',$branch_code)
                    ->update([
                            'amount_pay'=>$request->paying_amount,
                            'up_date'=>$request->invoice_date,
                            'cash_payment'=>$request->cash_payment,
                            'card_payment'=>$request->card_payment,
                            'cheque_payment'=>$request->cheque_payment,
                            'bank_transfer'=>$request->bank_transfer,
                        ]);

            }elseif($difference > 0){

                    TInstalment::where('invoice_no', $Invoice_no)
                        ->where('instalment_date', $row_date)
                        ->where('BC',$branch_code)
                        ->update([
                                'amount_pay'=>$request->paying_amount,
                                'up_date'=>$request->invoice_date,
                                'cash_payment'=>$request->cash_payment,
                                'card_payment'=>$request->card_payment,
                                'cheque_payment'=>$request->cheque_payment,
                                'bank_transfer'=>$request->bank_transfer,
                            ]);

                    $Installment_date = Carbon::parse($row_date);
                    $next_installment_date = $Installment_date->addMonth()->format('Y-m-d');
                    // $next_installment_date_formatted = $next_installment_date->format('Y-m-d');

                    TInstalment::where('invoice_no', $Invoice_no)
                        ->where('instalment_date', $next_installment_date)
                        ->update([
                            'instalment_amount' => DB::raw('instalment_amount - ' . $difference),
                            ]);

            }elseif($difference < 0){

                    TInstalment::where('invoice_no', $Invoice_no)
                        ->where('instalment_date', $row_date)
                        ->where('BC',$branch_code)
                        ->update([
                                'amount_pay'=>$request->paying_amount,
                                'up_date'=>$request->invoice_date,
                                'cash_payment'=>$request->cash_payment,
                                'card_payment'=>$request->card_payment,
                                'cheque_payment'=>$request->cheque_payment,
                                'bank_transfer'=>$request->bank_transfer,
                            ]);

                    $Installment_date = Carbon::parse($row_date);
                    $next_installment_date = $Installment_date->addMonth()->format('Y-m-d');
                    // $next_installment_date_formatted = $next_installment_date->format('Y-m-d');

                    TInstalment::where('invoice_no', $Invoice_no)
                        ->where('instalment_date', $next_installment_date)
                        ->update([
                            'instalment_amount' => DB::raw('instalment_amount - ' . $difference),
                            ]);

            }
        }

        $companyData = Company::latest()->paginate(1);

        $installment_raw_data = TInstalment::where('invoice_no', $Invoice_no)
                            ->where('instalment_date', $row_date)
                            ->where('BC',$branch_code)
                            ->get();

        foreach ($installment_raw_data as $installment) {
            $customerCode = $installment->customer_code;
        }

        //TODO: need to get customer data
        $customer_data = Customer::where('NIC', $customerCode)
                        ->paginate(1);

        $T_installment_data = TInstalment::where('invoice_no', $Invoice_no)
                            ->where('bc', $branch_code)
                            ->get();

        // Generate the PDF content using a view
        $pdf = PDF::loadView('installmentPaymentInvoicePrint', [
            'installment_raw_data' => $installment_raw_data ,
            'installmentData' => $T_installment_data,
            'customer_data' => $customer_data,
            'companyData' => $companyData
            ]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Installment_Payment_Invoice.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('public/assets/pdf/Installment_Payment_Invoice.pdf');

        return back()
        ->with('done','The instalment has been added')
        ->with("pdfLink", $pdfUrl);
    }


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
