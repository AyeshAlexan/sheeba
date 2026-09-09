<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TGentralReceipt extends Model
{
    use HasFactory;
    /**
     * Explicit table name for this model
     * (ensure it uses the existing DB table `t_gentral_receipts`)
     */
    protected $table = 't_gentral_receipts';

    protected $fillable = ['invoice_no', 'date','cramount',
    'crcode','dramount',
    'drcode','description',
    'amount','OC','BC'];
}