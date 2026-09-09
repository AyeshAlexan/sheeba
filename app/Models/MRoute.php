<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MRoute extends Model
{
    use HasFactory;
    protected $fillable = [ 'code','description','Branch','BranchCode'];
}
