<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWithoutVatSalesSum extends Model
{
    use HasFactory;
protected $fillable = [
    'Invoice_no',
    'Invoice_date',
    'ref_no',
    'Customer_NIC',
    'Customer_Name',
    'Customer_Phone',
    'customer_balance',
    'Route',
    'Salesmen',
    'Item',
    'Amount',
    'Advance',
    'Balance',
    'Gross_Amount',
    'Discount',
    'Net_Amount',
    'vat_presentage',
    'vat_amount',
    'after_vat_amount',
    'Cash_Pay',
    'Half_Payment',
    'Credite',
    'Cheque',
    'Paid_Amount',
    'OC',
    'BC',
    'serial_number',
];

}
