<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Make extends Model
{
    use HasFactory;
    protected $fillable = [ 'Make_code','Make_name','Branch','BranchCode'];
}
