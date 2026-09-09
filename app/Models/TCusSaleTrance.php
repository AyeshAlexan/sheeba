<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCusSaleTrance extends Model
{
    use HasFactory;
    protected $fillable = [
        'no',
        'customer',
        'dr_trnce_code',
        'dr_trnce_no',
        'cr_amount',
        'cr_trnce_code',
        'cr_trnce_no',
        'dr_amount',
        'trance_type',
        'trance_no',
        'dDate',
        'bc',
        'oc',
    ];
}