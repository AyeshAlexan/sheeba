<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSalesReturnDetails extends Model
{
    use HasFactory;

     protected $table      = 't_sales_return_details';  // must match exactly
    public    $timestamps = false;   
}