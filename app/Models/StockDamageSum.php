<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDamageSum extends Model
{
    use HasFactory;
    protected $fillable = ['Damage_no', 'Damage_date', 'Store_code', 'Total_qty', 'Total_value', 'BC', 'OC'];
}
