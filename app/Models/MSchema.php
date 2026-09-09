<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSchema extends Model
{
    use HasFactory;

    protected $fillable = [ 'code','SchemaType','InRate', 'DocumentCharage','PanaltyCharage', 'DownPayment','OC','BC'];

}
