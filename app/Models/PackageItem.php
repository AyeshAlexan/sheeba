<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    use HasFactory;

    protected $table = 'package_items';

    protected $fillable = [
        'package_id',
        'item_code',
        'pkg_code',
        'item_description',
        'unit_price',
        'qty'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}