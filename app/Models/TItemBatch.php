<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TItemBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'batch_no',
        'purchase_price',
        'sale_price',
        'is_auto_generated',
        'source_trans_code',
        'source_trans_no',
        'bc',
    ];
}
