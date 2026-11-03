<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAccountTrans extends Model
{
    use HasFactory;

    protected $fillable = [
        'trance_type',
        'Ddate',
        'AccCode',
        'customer',
        'Description',
        'dr_amount',
        'cr_amount',
        'trance_no',
        'no',
        'BC',
        'OC',
    ];
}
