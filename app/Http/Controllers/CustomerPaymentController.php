<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TCustomerPayment;
use App\Models\TInvoiceSum;
use App\Models\Customer;
use App\Models\TCusSaleTrance;
use App\Models\TCusCheque;
use App\Models\TAccountTrans;          // ← ADD THIS
use App\Models\BankDetails;
use App\Models\BankBranch;
use App\Models\ChequeBank;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class CustomerPaymentController extends Controller
{
    // ---------------------------------------------------------------
    // INDEX
    // ---------------------------------------------------------------
    public function index()
    {
        $Customer      = Customer::all();
        $Banks         = BankDetails::all();
        $Bank_branch   = BankBranch::all();
        $Customerdata  = Customer::all();
        $ChequeBanks   = ChequeBank::where('is_active', true)->get();

        $maxCustomerNo = TCustomerPayment::orderBy('Payment_no', 'desc')->value('Payment_no');
        $maxInvoiceNos = str_pad($maxCustomerNo, 4, '0', STR_PAD_LEFT);

        return view('customer_payment')
            ->with('customerDetails', $Customerdata)
            ->with('maxInvoiceNo',    $maxInvoiceNos)
            ->with('bank',            $Banks)
            ->with('bank_branch',     $Bank_branch)
            ->with('chequeBanks',     $ChequeBanks)
            ->with('customer',        $Customer);
    }

    // ---------------------------------------------------------------
    // INVOICE WISH: find per-invoice data
    // ---------------------------------------------------------------
    public function findCustomerPayment(Request $request)
    {
        $branch_code   = auth()->user()->BC;
        $supplier_code = $request->search_string;

        $purchases = DB::table('t_cus_sale_trances as t')
            ->join('t_without_vat_sales_sums as s', function ($join) use ($branch_code) {
                $join->on('t.no', '=', 's.Invoice_no')
                     ->where('t.bc', '=', $branch_code)
                     ->where('s.bc', '=', $branch_code);
            })
            ->where('s.Customer_NIC', $supplier_code)
            ->where('s.Credite', '>', 0)
            ->select(
                's.Invoice_no', 's.Invoice_date',
                's.Customer_NIC', 's.Customer_Name', 's.Customer_Phone',
                's.Credite'
            )
            ->groupBy(
                's.Invoice_no', 's.Invoice_date',
                's.Customer_NIC', 's.Customer_Name', 's.Customer_Phone',
                's.Credite'
            )
            ->get();

        $result = [];
        foreach ($purchases as $row) {
            $paid_amount = DB::table('t_cus_sale_trances')
                ->where('no',          $row->Invoice_no)
                ->where('bc',          $branch_code)
                ->where('trance_type', 'CR_CUS_PAY')
                ->sum('dr_amount');

            $result[] = [
                'Customer_Name'  => $row->Customer_Name,
                'Invoice_no'     => $row->Invoice_no,
                'Invoice_date'   => $row->Invoice_date,
                'credit_payment' => $row->Credite,
                'paid_amount'    => $paid_amount,
                'Customer_Phone' => $row->Customer_Phone,
                'Customer_NIC'   => $row->Customer_NIC,
            ];
        }

        if (!empty($result)) {
            return response()->json(['status' => 'success', 'data' => $result]);
        }
        return response()->json(['status' => 'not_found']);
    }

    // ---------------------------------------------------------------
    // TOTAL CREDIT: get balance
    // ---------------------------------------------------------------
    public function getTotalCreditBalance(Request $request)
    {
        $branch_code   = auth()->user()->BC;
        $customer_code = $request->search_string;

        $total_cr = TCusSaleTrance::where('customer', $customer_code)
            ->where('bc', $branch_code)->sum('cr_amount');

        $total_dr = TCusSaleTrance::where('customer', $customer_code)
            ->where('bc', $branch_code)->sum('dr_amount');

        $balance = $total_cr - $total_dr;

        if ($total_cr == 0) {
            return response()->json(['status' => 'not_found']);
        }

        $customer = Customer::where('Code', $customer_code)->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'customer_code'   => $customer_code,
                'customer_name'   => $customer ? $customer->First_name : '',
                'customer_phone'  => $customer ? $customer->Contact_1  : '',
                'total_cr_amount' => $total_cr,
                'total_dr_amount' => $total_dr,
                'balance'         => $balance,
            ],
        ]);
    }

    // ---------------------------------------------------------------
    // TOTAL CREDIT: save payment
    // ---------------------------------------------------------------
    public function makeTotalCreditPayment(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name   = auth()->user()->username;
        $dataArray   = json_decode($request->dataArray);

        $request->validate([
            'customer_code' => 'required',
            'payment_no'    => 'required|unique:t_customer_payments,Payment_no',
            'payment_date'  => 'required',
            'paying_amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $cash_payment   = (float) ($request->cash_payment        ?? 0);
            $card_payment   = (float) ($request->card_payment        ?? 0);
            $bank_transfer  = (float) ($request->bank_transfer       ?? 0);
            $cheque_payment = (float) ($request->total_cheque_amount  ?? 0);
            $TotalPayment   = $cash_payment + $card_payment + $bank_transfer + $cheque_payment;

            // ── 1. Save to t_customer_payments ───────────────────────────
            $CustomerPayment                  = new TCustomerPayment;
            $CustomerPayment->Sales_no        = $request->customer_code;
            $CustomerPayment->Payment_no      = $request->payment_no;
            $CustomerPayment->Payment_date    = $request->payment_date;
            $CustomerPayment->Customer_Code   = $request->customer_code;
            $CustomerPayment->Customer_Name   = $request->customer_name;
            $CustomerPayment->Customer_Phone  = $request->customer_phone;
            $CustomerPayment->Payment_note    = $request->payment_note;
            $CustomerPayment->Payment_Amount  = $request->total_cr;
            $CustomerPayment->cash_payment    = $cash_payment;
            $CustomerPayment->card_payment    = $card_payment;
            $CustomerPayment->Paided_amount   = $request->paying_amount;
            $CustomerPayment->cheque_payment  = $dataArray != null ? $cheque_payment : 0;
            $CustomerPayment->bank_transfer   = $bank_transfer;
            $CustomerPayment->BC              = $branch_code;
            $CustomerPayment->OC              = $user_name;
            $CustomerPayment->save();

            // ── 2. Customer-ledger transaction ───────────────────────────
            $TCusSaleTrance                  = new TCusSaleTrance;
            $TCusSaleTrance->no              = $request->customer_code;
            $TCusSaleTrance->customer        = $request->customer_code;
            $TCusSaleTrance->dr_trnce_code   = 'TCP_CUS_PAY';
            $TCusSaleTrance->dr_trnce_no     = $request->customer_code;
            $TCusSaleTrance->dr_amount       = $TotalPayment;
            $TCusSaleTrance->cr_trnce_code   = 'TCP_CUS_PAY';
            $TCusSaleTrance->cr_trnce_no     = $request->customer_code;
            $TCusSaleTrance->cr_amount       = 0;
            $TCusSaleTrance->trance_type     = 'TCP_CUS_PAY';
            $TCusSaleTrance->trance_no       = $request->customer_code;
            $TCusSaleTrance->Display_Ref     = $request->payment_no;
            $TCusSaleTrance->dDate           = $request->payment_date;
            $TCusSaleTrance->BC              = $branch_code;
            $TCusSaleTrance->OC              = $user_name;
            $TCusSaleTrance->save();

            // ── 3. Accounting double-entry (TCP_CUS_PAY) ─────────────────
            $ref  = $request->customer_code;
            $date = $request->payment_date;
            $cust = $request->customer_code;

            // A. Cheque payment  — CR only (clearing entry)
            if ($cheque_payment > 0) {
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, $cust,
                    $date, 0, $cheque_payment,
                    'Cheque Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // B. Cash payment  — DR cash control (201-001), CR customer
            if ($cash_payment > 0) {
                // DR: cash account
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, '201-001',
                    $date, $cash_payment, 0,
                    'Cash Payment Received',
                    $branch_code, $user_name
                );
                // CR: customer account
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, $cust,
                    $date, 0, $cash_payment,
                    'Cash Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // C. Card payment  — DR card control (201-002), CR customer
            if ($card_payment > 0) {
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, '201-002',
                    $date, $card_payment, 0,
                    'Card Payment Received',
                    $branch_code, $user_name
                );
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, $cust,
                    $date, 0, $card_payment,
                    'Card Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // D. Bank transfer — DR bank control (201-003), CR customer
            if ($bank_transfer > 0) {
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, '201-003',
                    $date, $bank_transfer, 0,
                    'Bank Transfer Received',
                    $branch_code, $user_name
                );
                $this->insertAccountTrans(
                    'TCP_CUS_PAY', $ref, $cust,
                    $date, 0, $bank_transfer,
                    'Bank Transfer Adjustment',
                    $branch_code, $user_name
                );
            }

            // ── 4. Save cheques ──────────────────────────────────────────
            if (is_array($dataArray) || is_object($dataArray)) {
                $maxchequeNo = TCusCheque::where('bc', $branch_code)
                    ->orderBy('trans_order_no', 'desc')
                    ->value('trans_order_no');
                $maxInvoice = $maxchequeNo ? (int) $maxchequeNo : 0;

                // The ledger entry above already reflects this payment, so the
                // customer's outstanding balance right now is what's still
                // pending after it — record that alongside each cheque saved.
                $custTotalCr = TCusSaleTrance::where('customer', $request->customer_code)->sum('cr_amount');
                $custTotalDr = TCusSaleTrance::where('customer', $request->customer_code)->sum('dr_amount');
                $custPending = round($custTotalCr - $custTotalDr, 2);
                $custIsPartial = $custPending > 0.01;

                foreach ($dataArray as $value) {
                    $cheque                 = new TCusCheque;
                    $cheque->trans_no       = $request->customer_code;
                    $cheque->trans_type     = 'CUS_RECEIPT_TCP';
                    $cheque->cheque_status  = 'P';
                    $cheque->bank           = $value->bank_name;
                    $cheque->cheque_bank_id = $value->cheque_bank_id ?? null;
                    $cheque->acc_no         = $value->account_no ?? null;
                    $cheque->cheques_no     = $value->cheque_no;
                    $cheque->release_date   = $value->cheque_date;
                    $cheque->amount         = $value->cheque_ammount;
                    $cheque->is_partial_payment = $custIsPartial;
                    $cheque->pending_amount     = $custPending;
                    $cheque->trans_order_no = str_pad($maxInvoice + 1, 4, '0', STR_PAD_LEFT);
                    $cheque->oc             = $user_name;
                    $cheque->bc             = $branch_code;
                    $cheque->save();
                    $maxInvoice++;
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->errors()['payment_no'][0] ?? 'Validation failed!',
                'errors'  => $e->errors(),
            ], 422);

        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This payment number is already used. Please enter a unique one.',
                ], 422);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ---------------------------------------------------------------
    // INVOICE WISH: save payment
    // ---------------------------------------------------------------
    public function create(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $user_name   = auth()->user()->username;
        $dataArray   = json_decode($request->dataArray);

        $request->validate([
            'customer_code' => 'required',
            'payment_no'    => 'required|unique:t_customer_payments,Payment_no',
            'payment_date'  => 'required',
            'sales_date'    => 'required',
        ]);

        try {
            DB::beginTransaction();

            $cash_payment   = (float) ($request->cash_payment        ?? 0);
            $card_payment   = (float) ($request->card_payment        ?? 0);
            $bank_transfer  = (float) ($request->bank_transfer       ?? 0);
            $cheque_payment = (float) ($request->total_cheque_amount  ?? 0);
            $TotalPayment   = $cash_payment + $card_payment + $bank_transfer + $cheque_payment;

            // ── 1. Save to t_customer_payments ───────────────────────────
            $CustomerPayment                  = new TCustomerPayment;
            $CustomerPayment->Sales_no        = $request->sales_no;
            $CustomerPayment->Payment_no      = $request->payment_no;
            $CustomerPayment->Payment_date    = $request->payment_date;
            $CustomerPayment->Customer_Code   = $request->customer_code;
            $CustomerPayment->Customer_Name   = $request->customer_name;
            $CustomerPayment->Customer_Phone  = $request->customer_phone;
            $CustomerPayment->Payment_note    = $request->payment_note;
            $CustomerPayment->Payment_Amount  = $request->amount;
            $CustomerPayment->cash_payment    = $cash_payment;
            $CustomerPayment->card_payment    = $card_payment;
            $CustomerPayment->Paided_amount   = $request->paying_amount;
            $CustomerPayment->cheque_payment  = $dataArray != null ? $cheque_payment : 0;
            $CustomerPayment->bank_transfer   = $bank_transfer;
            $CustomerPayment->bc              = $branch_code;
            $CustomerPayment->OC              = $user_name;
            $CustomerPayment->save();

            // ── 2. Customer-ledger transaction ───────────────────────────
            $TCusSaleTrance                  = new TCusSaleTrance;
            $TCusSaleTrance->no              = $request->sales_no;
            $TCusSaleTrance->customer        = $request->customer_code;
            $TCusSaleTrance->dr_trnce_code   = 'CR_CUS_PAY';
            $TCusSaleTrance->dr_trnce_no     = $request->sales_no;
            $TCusSaleTrance->dr_amount       = $TotalPayment;
            $TCusSaleTrance->cr_trnce_code   = 'CR_CUS_PAY';
            $TCusSaleTrance->cr_trnce_no     = $request->sales_no;
            $TCusSaleTrance->cr_amount       = 0;
            $TCusSaleTrance->trance_type     = 'CR_CUS_PAY';
            $TCusSaleTrance->trance_no       = $request->sales_no;
            $TCusSaleTrance->Display_Ref     = $request->payment_no;
            $TCusSaleTrance->dDate           = $request->payment_date;
            $TCusSaleTrance->bc              = $branch_code;
            $TCusSaleTrance->oc              = $user_name;
            $TCusSaleTrance->save();

            // ── 3. Accounting double-entry (CR_CUS_PAY) ──────────────────
            $ref  = $request->sales_no;
            $date = $request->payment_date;
            $cust = $request->customer_code;

            // A. Cheque payment — CR only (clearing entry)
            if ($cheque_payment > 0) {
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, $cust,
                    $date, 0, $cheque_payment,
                    'Cheque Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // B. Cash payment — DR cash control (201-001), CR customer
            if ($cash_payment > 0) {
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, '201-001',
                    $date, $cash_payment, 0,
                    'Cash Payment Received',
                    $branch_code, $user_name
                );
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, $cust,
                    $date, 0, $cash_payment,
                    'Cash Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // C. Card payment — DR card control (201-002), CR customer
            if ($card_payment > 0) {
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, '201-002',
                    $date, $card_payment, 0,
                    'Card Payment Received',
                    $branch_code, $user_name
                );
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, $cust,
                    $date, 0, $card_payment,
                    'Card Payment Adjustment',
                    $branch_code, $user_name
                );
            }

            // D. Bank transfer — DR bank control (201-003), CR customer
            if ($bank_transfer > 0) {
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, '201-003',
                    $date, $bank_transfer, 0,
                    'Bank Transfer Received',
                    $branch_code, $user_name
                );
                $this->insertAccountTrans(
                    'CR_CUS_PAY', $ref, $cust,
                    $date, 0, $bank_transfer,
                    'Bank Transfer Adjustment',
                    $branch_code, $user_name
                );
            }

            // ── 4. Save cheques ──────────────────────────────────────────
            if (is_array($dataArray) || is_object($dataArray)) {
                $maxchequeNo = TCusCheque::where('bc', $branch_code)
                    ->orderBy('trans_order_no', 'desc')->value('trans_order_no');
                $maxInvoice = $maxchequeNo ? (int) $maxchequeNo : 0;

                // The ledger entry above already reflects this payment, so the
                // customer's outstanding balance right now is what's still
                // pending after it — record that alongside each cheque saved.
                $custTotalCr = TCusSaleTrance::where('customer', $request->customer_code)->sum('cr_amount');
                $custTotalDr = TCusSaleTrance::where('customer', $request->customer_code)->sum('dr_amount');
                $custPending = round($custTotalCr - $custTotalDr, 2);
                $custIsPartial = $custPending > 0.01;

                foreach ($dataArray as $value) {
                    $supplyerCheque                 = new TCusCheque;
                    $supplyerCheque->trans_no       = $request->sales_no;
                    $supplyerCheque->trans_type     = 'CUS_RECEIPT';
                    $supplyerCheque->cheque_status  = 'P';
                    $supplyerCheque->bank           = $value->bank_name;
                    $supplyerCheque->cheque_bank_id = $value->cheque_bank_id ?? null;
                    $supplyerCheque->acc_no         = $value->account_no ?? null;
                    $supplyerCheque->cheques_no     = $value->cheque_no;
                    $supplyerCheque->release_date   = $value->cheque_date;
                    $supplyerCheque->amount         = $value->cheque_ammount;
                    $supplyerCheque->is_partial_payment = $custIsPartial;
                    $supplyerCheque->pending_amount     = $custPending;
                    $supplyerCheque->trans_order_no = str_pad($maxInvoice + 1, 4, '0', STR_PAD_LEFT);
                    $supplyerCheque->oc             = $user_name;
                    $supplyerCheque->bc             = $branch_code;
                    $supplyerCheque->save();
                    $maxInvoice++;
                }
            }

            // ── 5. Update invoice paid amount ────────────────────────────
            $dataPaided      = TInvoiceSum::where('Customer_NIC', $request->customer_code)
                ->where('Invoice_no', $request->sales_no)
                ->whereNotNull('Paid_Amount')
                ->where('bc', $branch_code)->get();
            $totalPaidAmount = $dataPaided->sum('Paid_Amount') + $TotalPayment;

            TInvoiceSum::where('Invoice_no', $request->sales_no)
                ->where('BC', $branch_code)
                ->update(['Paid_Amount' => $totalPaidAmount]);

            DB::commit();
            return response()->json(['status' => 'success']);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->errors()['payment_no'][0] ?? 'Validation failed!',
                'errors'  => $e->errors(),
            ], 422);

        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This payment number is already used. Please enter a unique one.',
                ], 422);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ---------------------------------------------------------------
    // SHARED: insert one row into t_account_trans
    //
    // @param string      $trance_type  e.g. 'CR_CUS_PAY' / 'TCP_CUS_PAY'
    // @param string      $ref          invoice no or customer code
    // @param string      $acc_code     account code (or customer code as ledger key)
    // @param string      $date         transaction date
    // @param float       $dr           debit amount  (0 if none)
    // @param float       $cr           credit amount (0 if none)
    // @param string      $desc         human-readable description
    // @param string      $bc           branch code
    // @param string      $oc           operator code
    // ---------------------------------------------------------------
    private function insertAccountTrans(
        string $trance_type,
        string $ref,
        string $acc_code,
        string $date,
        float  $dr,
        float  $cr,
        string $desc,
        string $bc,
        string $oc
    ): void {
        $at               = new TAccountTrans;
        $at->trance_type  = $trance_type;
        $at->Ddate        = $date;
        $at->AccCode      = $acc_code;
        $at->Description  = $desc;
        $at->dr_amount    = $dr;
        $at->cr_amount    = $cr;
        $at->trance_no    = $ref;
        $at->no           = $ref;
        $at->BC           = $bc;
        $at->OC           = $oc;
        $at->save();
    }

    // ---------------------------------------------------------------
    // EXISTING helpers
    // ---------------------------------------------------------------
    public function findCustomer(Request $request)
    {
        $branch_code = auth()->user()->BC;
        $supplier_code = $request->search_string;

        $dataCredite = TInvoiceSum::where('Customer_NIC', $supplier_code)
            ->whereNotNull('Credite')->where('bc', $branch_code)->get();
        $dataPaided  = TInvoiceSum::where('Customer_NIC', $supplier_code)
            ->whereNotNull('Paid_Amount')->where('bc', $branch_code)->get();

        $purchase_total   = $dataCredite->sum('Credite');
        $amount_pay_total = $dataCredite->sum('amount_pay');
        $Paid_Amount      = $dataPaided->sum('Paid_Amount');

        $data = ($purchase_total > $Paid_Amount)
            ? TInvoiceSum::where('Customer_NIC', $supplier_code)
                ->whereNotNull('Credite')->where('bc', $branch_code)->get()
            : collect();

        if ($data->count() != 0) {
            return response()->json([
                'status'           => 'success',
                'data'             => $data,
                'purchase_total'   => $purchase_total,
                'amount_pay_total' => $amount_pay_total,
            ]);
        }
        return response()->json(['status' => 'not_found']);
    }

    public function show($id) {}
    public function edit($id) {}
}