<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChequeBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch',
        'account_name',
        'account_no',
        'opening_amount',
        'current_amount',
        'is_active',
        'OC',
        'BC',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
