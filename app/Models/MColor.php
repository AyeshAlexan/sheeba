<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MColor extends Model
{
    use HasFactory;
    protected $fillable = [ 'Color_code','Color_name','Branch','BranchCode'];
}
