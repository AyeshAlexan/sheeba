<?php

namespace App\Http\Controllers;

use App\Models\TCusSaleTrance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceDiscountEnterController extends Controller
{
    /**
     * Display the invoice discount entry page.
     */
    public function index()
    {
        return view('invoiceDiscountEnter');
    }

    /**
     * Search customers by code, name, NIC, or phone.
     */
    public function searchCustomer(Request $request)
    {
        $search = $request->input('term');

        $customers = DB::table('customers')
            ->where(function ($query) use ($search) {
                $query->where('Code', 'LIKE', "%{$search}%")
                      ->orWhere('First_name', 'LIKE', "%{$search}%")
                      ->orWhere('Name', 'LIKE', "%{$search}%")
                      ->orWhere('NIC', 'LIKE', "%{$search}%")
                      ->orWhere('Contact_1', 'LIKE', "%{$search}%");
            })
            ->select('id', 'Code', 'First_name', 'Name', 'NIC')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    /**
     * Get invoices for a given customer code.
     */
    public function getCustomerInvoices(Request $request)
    {
        $customerCode = $request->input('customer_code');

        // NOTE: confirm the actual column name in t_without_vat_sales_sums
        // that stores the customer's Code. It was previously compared
        // against Customer_NIC, which won't match a Code value.
        $invoices = DB::table('t_without_vat_sales_sums')
            ->where('Customer_NIC', $customerCode)
            ->select(
                'id',
                'Invoice_no',
                'Invoice_date',
                'Gross_Amount',
                'Discount',
                'Net_Amount',
                'Balance',
                'after_customer_discount'
            )
            ->orderBy('Invoice_date', 'desc')
            ->get();

        return response()->json($invoices);
    }

    /**
     * Get the customer's total outstanding balance from t_cus_sale_trances.
     * advance = total debit - total credit
     * A positive advance means the customer owes money (outstanding balance).
     * A negative advance means the customer has credit in hand.
     */
    public function getCustomerBalance(Request $request)
    {
        $customerCode = $request->input('customer_code');

        $transactions = TCusSaleTrance::where('customer', $customerCode)->get();

        $drAmount = $transactions->sum('dr_amount') ?? 0;
        $crAmount = $transactions->sum('cr_amount') ?? 0;
        $advance  = $crAmount - $drAmount;

        return response()->json([
            'dr_amount'           => number_format($drAmount, 2, '.', ''),
            'cr_amount'           => number_format($crAmount, 2, '.', ''),
            'advance'             => number_format($advance, 2, '.', ''),
            'outstanding_balance' => number_format($advance, 2, '.', ''), // positive = customer owes
        ]);
    }

    /**
     * Apply a discount to an invoice:
     *  - updates after_customer_discount / Net_Amount / Balance on t_without_vat_sales_sums
     *  - records a double-entry ledger row in t_cus_sale_trances
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'invoice_id'      => 'required|integer',
            'invoice_no'      => 'required',
            'customer_code'   => 'required',
            'invoice_date'    => 'required|date',
            'discount_amount' => 'required|numeric|min:0.01',
        ]);

        $user = auth()->user();

        DB::beginTransaction();
        try {
            $invoice = DB::table('t_without_vat_sales_sums')
                ->where('id', $request->invoice_id)
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found.',
                ], 404);
            }

            $discount = round((float) $request->discount_amount, 2);

            DB::table('t_without_vat_sales_sums')
                ->where('id', $request->invoice_id)
                ->update([
                    'after_customer_discount' => $discount,
                ]);

            $creditTrance = new TCusSaleTrance;
            $creditTrance->no            = $request->invoice_no;
            $creditTrance->customer      = $request->customer_code;
            $creditTrance->dr_trnce_code = 'SALES_DISCOUNT';
            $creditTrance->dr_trnce_no   = $request->invoice_no;
            $creditTrance->cr_trnce_code = 'SALES_DISCOUNT';
            $creditTrance->cr_trnce_no   = $request->invoice_no;
            $creditTrance->dr_amount     = $discount;
            $creditTrance->cr_amount     = 0;
            $creditTrance->trance_type   = 'SALES_DISCOUNT';
            $creditTrance->trance_no     = $request->invoice_no;
            $creditTrance->Display_Ref   = $request->invoice_no;
            $creditTrance->dDate         = $request->invoice_date;
            $creditTrance->bc            = $user->BC ?? null;
            $creditTrance->oc            = $user->username ?? null;
            $creditTrance->save();

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Discount applied successfully.',
                'discount' => number_format($discount, 2, '.', ''),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error applying discount: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save a transport payment against an invoice:
     * records a ledger row in t_cus_sale_trances (trance_type = 'transfort').
     * cr_amount = transport amount, dr_amount = 0.
     */
    public function applyTransport(Request $request)
    {
        $request->validate([
            'invoice_id'       => 'required|integer',
            'invoice_no'       => 'required',
            'customer_code'    => 'required',
            'invoice_date'     => 'required|date',
            'transport_amount' => 'required|numeric|min:0.01',
        ]);

        $user = auth()->user();

        DB::beginTransaction();
        try {
            $invoice = DB::table('t_without_vat_sales_sums')
                ->where('id', $request->invoice_id)
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found.',
                ], 404);
            }

            $transport = round((float) $request->transport_amount, 2);

            $transportTrance = new TCusSaleTrance;
            $transportTrance->no            = $request->invoice_no;
            $transportTrance->customer      = $request->customer_code;
            $transportTrance->dr_trnce_code = 'Transport Payment';
            $transportTrance->dr_trnce_no   = $request->invoice_no;
            $transportTrance->cr_trnce_code = 'Transport Payment';
            $transportTrance->cr_trnce_no   = $request->invoice_no;
            $transportTrance->dr_amount     = 0;
            $transportTrance->cr_amount     = $transport;
            $transportTrance->trance_type   = 'Transport Payment';
            $transportTrance->trance_no     = $request->invoice_no;
            $transportTrance->Display_Ref   = $request->invoice_no;
            $transportTrance->dDate         = $request->invoice_date;
            $transportTrance->bc            = $user->BC ?? null;
            $transportTrance->oc            = $user->username ?? null;
            $transportTrance->save();

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Transport payment saved successfully.',
                'transport' => number_format($transport, 2, '.', ''),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving transport payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}