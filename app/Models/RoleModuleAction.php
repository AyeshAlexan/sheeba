<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModuleAction extends Model
{
    use HasFactory;
    protected $fillable = ['role_name', 'module_key', 'action_key', 'is_enabled'];
    protected $casts = ['is_enabled' => 'boolean'];
}
