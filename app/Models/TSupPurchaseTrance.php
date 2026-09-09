<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSupPurchaseTrance extends Model
{
    use HasFactory;

 protected $fillable = [
    'no',
    'supplier',
    'dr_trnce_code',
    'dr_trnce_no',
    'dr_amount',
    'cr_trnce_code',
    'cr_trnce_no',
    'cr_amount',
    'trance_type',
    'trance_no',
    'dDate',
    'bc',
    'oc',
];

}