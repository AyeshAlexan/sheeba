<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JobSheet;
use App\Models\Item;
use App\Models\Category;
use App\Models\TInvoiceSum;
use App\Models\TInvoiceDeils;
use App\Models\TItemMovement;
use App\Models\MSchema;
use App\Models\TOpeningHirePurchaseSum;
use App\Models\TOpeningHirePurchaseDetails;
use App\Models\THirePurchaseSum;
use App\Models\THirePurchaseDetails;
use App\Models\TInstalment;
use App\Models\MGuarantor;
use App\Models\TAccountTran;
use App\Models\DownPayment;


class HirePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        // get max invoice number max
        $maxInvoiceNo = THirePurchaseSum::orderBy('invoice_no', 'desc')
                        ->where('BC',$branch_code)
                        ->value('invoice_no');
        $maxInvoice = str_pad($maxInvoiceNo, 4, '0', STR_PAD_LEFT);

         // get max down payment number
        $maxDownPaymentNo = DownPayment::orderBy('payment_no', 'desc')
                            ->where('bc',$branch_code)
                            ->value('payment_no');
        $maxDownPayment = str_pad($maxDownPaymentNo, 4, '0', STR_PAD_LEFT)+1;

        // Get the current date and time
        $currentDateTime = now();
        $currentYear = $currentDateTime->year;
        $currentMonth = $currentDateTime->month;

        $nextAgreementNo = $currentYear. '/' .$currentMonth. '/' ."SEN" . '/' .$maxInvoice+1;

        $companyData = Company::latest()->paginate(1);
        $itemCode = Item::all();
        $schemaDetails = MSchema::all();
        $itemCategory = Category::all();
        $itemDetails = Item::all();
        $customerDetails = Customer::all();

        // where('BC', $branch_code)->get();
        // get max Customer number code
        $maxCustomerNo = Customer::orderBy('Code', 'desc')->value('Code');
        $maxCustomerNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

         // get max Customer number code
         $maxGuarantorNo = MGuarantor::orderBy('Code', 'desc')->value('Code');
         $maxGuarantorNos = str_pad($maxGuarantorNo, 4, '0', STR_PAD_LEFT);

        return view('hirePurchase')
        ->with("itemCategory" , $itemCategory)
        ->with("itemCode" , $itemCode)
        ->with("companyData" , $companyData)
        ->with("maxCustomer", $maxCustomerNos)
        ->with("maxDownPayment", $maxDownPayment)
        ->with("maxGuarantor", $maxGuarantorNos)
        ->with("schemaDetails", $schemaDetails)
        ->with("itemDetails", $itemDetails)
        ->with("customerDetails", $customerDetails)
        ->with("nextAgreementNo", $nextAgreementNo)
        ->with("maxInvoiceNo", $maxInvoice);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'customer_code'=>'required ',
            'invoice_no'=>'required',
            'invoice_date'=>'required',
            'customer_name'=>'required',
            'schema_type'=>'required',
            'document_charge'=>'required',
            'down_payment'=>'required',
            'int_amount'=>'required',
            'no_of_inst'=>'required',
            'inst_due_date'=>'required',
            'gross_amount'=>'required',
            'net_amount'=>'required',
        ]);

        $request->validate([
            'inputs.*.invoice_no' => ' required',
            'inputs.*.invoice_date' => ' required',
            // 'inputs.*.item_category' => ' required',
            'inputs.*.item_code' => ' required',
            'inputs.*.item_description' => ' required',
            'inputs.*.qty' => ' required',
            'inputs.*.unit_price' => ' required',
            'inputs.*.net_value' => ' required'
        ],[
            'inputs.*.customer_nic' => ' The Job No field is required',
            'inputs.*.invoice_no' => ' The Invoice No field is required',
            'inputs.*.invoice_date' => ' The Invoice Date field is required',
            // 'inputs.*.item_category' => ' The Item Category field is required',
            'inputs.*.item_code' => ' The Item Code field is required',
            'inputs.*.item_description' => ' The Item Description field is required',
            'inputs.*.qty' => ' The QTY field is required',
            'inputs.*.unit_price' => ' The Unit Price field is required',
            'inputs.*.net_value' => ' The Net Value field is required'
        ]);

        $InvoiceSum = new THirePurchaseSum;
        $InvoiceSum->invoice_no = $request->invoice_no;
        $InvoiceSum->reference_no = $request->ref_no;
        $InvoiceSum->agreement_no = $request->agreement_no;
        $InvoiceSum->invoice_date = $request->invoice_date;
        $InvoiceSum->customer_code = $request->customer_code;
        $InvoiceSum->customer_name = $request->customer_name;
        $InvoiceSum->customer_nic = $request->customer_nic;
        $InvoiceSum->customer_phone = $request->customer_phone;
        $InvoiceSum->customer_address = $request->customer_address;
        $InvoiceSum->guarantor_1_code = $request->guarantor_1_code;
        $InvoiceSum->guarantor_2_code = $request->guarantor_2_code;

        $InvoiceSum->schema_type = $request->schema_type;
        $InvoiceSum->document_charge_rate = $request->document_charge_rate;
        $InvoiceSum->document_charge = $request->document_charge;
        $InvoiceSum->down_payment_rate = $request->down_payment_rate;
        $InvoiceSum->down_payment = $request->cus_payment;
        $InvoiceSum->transport = $request->transport;
        $InvoiceSum->instalment_rate = $request->int_rate;
        $InvoiceSum->instalment_amount = $request->int_amount;
        $InvoiceSum->serial_number =  $request->serial_numbers_text;
        $InvoiceSum->no_of_instalment = $request->no_of_inst;
        $InvoiceSum->instalment_due_date = $request->inst_due_date;
        $InvoiceSum->instalment = $request->instalment;
        $InvoiceSum->due_amount = $request->final_amount;
        $InvoiceSum->final_gross_amount = $request->final_gross_amount;

        $InvoiceSum->gross_amount = $request->gross_amount;
        $InvoiceSum->discount = $request->discount;
        $InvoiceSum->net_amount = $request->net_amount;
        $InvoiceSum->cash_payment = $request->cash_payment;
        $InvoiceSum->card_payment = $request->card_payment;
        $InvoiceSum->cheque_payment = $request->cheque_payment;
        $InvoiceSum->bank_transfer = $request->bank_transfer;
        $InvoiceSum->oc = auth()->user()->username;
        $InvoiceSum->bc = auth()->user()->BC;
        $InvoiceSum->save();


        $no_of_instalment = $request->no_of_inst;
        $invoice_date = Carbon::parse($request->invoice_date);

        for ($i = 1; $i <= $no_of_instalment; $i++) {
            $instalment = new TInstalment;
            $instalment->invoice_no = $request->invoice_no;
            $instalment->agreement_no = $request->agreement_no;
            $instalment->invoice_date = $request->invoice_date;
            $instalment->customer_code = $request->customer_code;
            $instalment->customer_name = $request->customer_name;
            $instalment->schema_type = $request->schema_type;

            // Calculate instalment_date
            $instalment->instalment_date = $invoice_date->copy()->addMonths($i)->format('Y-m-d');
            $instalment->instalment_amount = $request->instalment;
            $instalment->amount_pay = "0";
            $instalment->oc = auth()->user()->username;
            $instalment->bc = auth()->user()->BC;
            $instalment->save();
        }

        foreach ($request -> inputs as $key=>$value){
        $InvoiceDetails = new THirePurchaseDetails;
        $InvoiceDetails->invoice_no=$value['invoice_no'];
        $InvoiceDetails->invoice_date=$value['invoice_date'];
        $InvoiceDetails->item_code=$value['item_code'];
        $InvoiceDetails->Item_s_code=$value['item_s_code'];
        $InvoiceDetails->item_description=$value['item_description'];
        $InvoiceDetails->unit_price=$value['unit_price'];
        $InvoiceDetails->qty=$value['qty'];
        $InvoiceDetails->discount_precentage=$value['discount'];
        $InvoiceDetails->discount=$value['discount_val'];
        $InvoiceDetails->net_value=$value['net_value'];
        $InvoiceDetails->oc= auth()->user()->username;
        $InvoiceDetails->bc= auth()->user()->BC;
        $InvoiceDetails->save();

        $ItemMovementDetails = new TItemMovement;
        $ItemMovementDetails->trans_no = $request->invoice_no;
        $ItemMovementDetails->dDate = $request->invoice_date;
        $ItemMovementDetails->trans_code="HP_SALES";
        $ItemMovementDetails->item_code=$value['item_code'];
        $ItemMovementDetails->qun_out=$value['qty'];
        $ItemMovementDetails->bc = auth()->user()->BC;
        $ItemMovementDetails->save();

        $AccountTran = new TAccountTran;
        $AccountTran->trance_type = "HP_SALES";
        $AccountTran->Ddate = $request->invoice_date;
        $AccountTran->AccCode = $request->customer_nic;
        $AccountTran->Description = $value['item_description'];
        $AccountTran->cr_amount = $request->cus_payment;
        $AccountTran->oc = auth()->user()->username;
        $AccountTran->bc = auth()->user()->BC;
        $AccountTran->trance_no = $request->invoice_no;
        $AccountTran->no = $request->invoice_no;
        $AccountTran->save();


        }

        $companyData = Company::latest()->paginate(1);

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $invoiceInput_no = $request->invoice_no;
        $T_detailsdata = THirePurchaseDetails::where('invoice_no', $invoiceInput_no)
                        ->where('bc', $branch_code)
                        ->get();

        $T_detailsdata_net_total = THirePurchaseDetails::where('invoice_no', $invoiceInput_no)
                                    ->where('bc', $branch_code)
                                    ->sum('net_value');

        $T_sumdata = THirePurchaseSum::where('invoice_no', $invoiceInput_no)
                    ->where('bc', $branch_code)
                    ->get();

        $T_installment_data = TInstalment::where('invoice_no', $invoiceInput_no)
                    ->where('bc', $branch_code)
                    ->get();

        // Generate the PDF content using a view
        $pdf = PDF::loadView('hirePurchaseInvoicePrint', [
            'hirePurchaseSumData' => $T_sumdata ,
            'hirePurchaseDetailsData' => $T_detailsdata,
            'hirePurchaseDetailsNetTotal' => $T_detailsdata_net_total,
            'installmentData' => $T_installment_data,
            'companyData' => $companyData
            ]);

        // Save the PDF to a temporary file
        $pdfPath = storage_path('../public/assets/pdf/Hire_Purchase_Invoice.pdf');
        $pdf->save($pdfPath);
        $pdfUrl = asset('public/assets/pdf/Hire_Purchase_Invoice.pdf');

        return back()
        ->with('done','The Invoice has been added')
        ->with("pdfLink", $pdfUrl);
    }


    public function findHPInvoice(Request $request)
    {
        $branch_code = auth()->user()->BC;

        $receiptNo = $request->search_hp_invoice_no;
        $data = THirePurchaseDetails::where('invoice_no',$receiptNo)
                    ->where('is_cash_converted',0)
                    ->where('BC',$branch_code)
                    ->get();
        if($data->count() != null){
            return view('invoice_find_hp_Invoice_details')
            // ->with("itemsDetails" , $itemsData)
            ->with('invoiceData', $data);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }

    }


    public function findHPInvoiceSumData(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $receiptNo = $request->search_hp_invoice_no;
        $data = THirePurchaseSum::where('invoice_no',$receiptNo)
                ->where('is_cash_converted',0)
                ->where('BC',$branch_code)
                ->get();
        if($data->count() != null){
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'status'=>'not_found'
            ]);
        }

    }

    // print Invoice ajax
    public function print(Request $request)
    {
        $invoiceInput_no = $request->hp_invoice_no;
        $companyData = Company::latest()->paginate(1);

        $branch_code = auth()->user()->BC;
        $user_name = auth()->user()->username;

        $T_detailsdata = THirePurchaseDetails::where('invoice_no', $invoiceInput_no)
                        ->where('bc', $branch_code)
                        ->get();

        $T_detailsdata_net_total = THirePurchaseDetails::where('invoice_no', $invoiceInput_no)
                                    ->where('bc', $branch_code)
                                    ->sum('net_value');

        $T_sumdata = THirePurchaseSum::where('invoice_no', $invoiceInput_no)
                    ->where('bc', $branch_code)
                    ->get();

        $T_installment_data = TInstalment::where('invoice_no', $invoiceInput_no)
                    ->where('bc', $branch_code)
                    ->get();

        if($T_sumdata->count() != null){
            // Generate the PDF content using a view
            $pdf = PDF::loadView('hirePurchaseInvoicePrint', [
                'hirePurchaseSumData' => $T_sumdata ,
                'hirePurchaseDetailsData' => $T_detailsdata,
                'hirePurchaseDetailsNetTotal' => $T_detailsdata_net_total,
                'installmentData' => $T_installment_data,
                'companyData' => $companyData
                ]);

            // Save the PDF to a temporary file
            $pdfPath = storage_path('../public/assets/pdf/Hire_Purchase_Re_Invoice.pdf');
            $pdf->save($pdfPath);
            $pdfUrl = asset('public/assets/pdf/Hire_Purchase_Re_Invoice.pdf');

            return response()->json(['status' => 'success', 'pdfUrl' => $pdfUrl]);
        }else{
            return response()->json(['status' => 'not_found']);
        }
    }


    public function store(Request $request)
    {
        //
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
