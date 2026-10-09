<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TItemMovement extends Model
{
    use HasFactory;
    // app/Models/TItemMovement.php

protected $fillable = [
    'trans_no',
    'dDate',
    'trans_code',
    'item_code',
    'batch_no',
    'qun_in',
    'qun_out',
    'Free_Issues',
    'bc',

];

}