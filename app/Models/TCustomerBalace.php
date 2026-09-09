<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCustomerBalace extends Model
{
    use HasFactory;
    protected $fillable =['invoice_no','date', 'customerCode', 'customerName', 'description', 'amount', 'OC', 'BC'];
}
