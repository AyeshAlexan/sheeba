<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'package_name',
        'category',
        'pkg_code',
        'Department',
        'Branch',
        'BranchCode',
    ];

    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }
}