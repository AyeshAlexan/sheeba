<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'date',
        'dr_code',
        'dr_label',
        'cr_code',
        'cr_label',
        'description',
        'amount',
        'party_type',
        'party_code',
        'party_side',
        'BC',
        'OC',
    ];
}
