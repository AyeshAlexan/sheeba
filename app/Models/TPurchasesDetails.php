<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPurchasesDetails extends Model
{
    use HasFactory;
            protected $fillable = [
    'Invoice_no', 'Ref_no', 'Invoice_date', 'Item_code', 'Item_s_code',
    'Item_description', 'QTY', 'Unit_price', 'Net_value', 'OC', 'BC'
];
}