<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAdvancCusPayment extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_no', 'date','customer_name', 'cus_code','description','amount','OC','BC'];
}
