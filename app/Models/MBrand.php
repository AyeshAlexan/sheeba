<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBrand extends Model
{
    use HasFactory;
    protected $fillable = [ 'Brand_code','Brand_name','Branch','BranchCode'];
    
}
