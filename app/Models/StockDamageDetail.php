<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDamageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'Damage_no', 'Item_code', 'Item_description', 'QTY', 'Unit_price', 'Net_value', 'Reason', 'BC', 'OC',
    ];
}
