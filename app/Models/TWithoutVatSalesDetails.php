<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWithoutVatSalesDetails extends Model
{
    use HasFactory;

        protected $fillable = [
    'Invoice_no',
    'Invoice_date', // ✅ this allows mass assignment
    'Item_category',
    'Item_code',
    'Item_s_code',
    'Item_description',
    'QTY',
    'Unit_price',
    'Free_Issues',
    'Discount',
    'Net_value',
    'OC',
    'BC'
];

}