<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'Customer_NIC',
        'Customer_Name',
        'Customer_Phone',
        'Job_no',
        'Date',
        'Brand',
        'Device_Model',
        'IMEI_Number',
        'Item',
        'Amount',
        'Advance',
        'Balance',
        'Problem',
        'Serial_Number',
        'Password',
        'Product_Configuration',
        'Problem_Reported',
        'Product_Condition',
        'OC',
        'BC'];
}
