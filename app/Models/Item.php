<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [ 'category','Department','Item_code','Per','Bar_code', 'Item_description','Brand','Color','Make','purchasePrice', 'saleprice','Credit','Inactive','ReorderLevel','RecorderQuantitiy','SaleDecimal','Serialnumber','Branch','BranchCode'];
}