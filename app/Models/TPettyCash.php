<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPettyCash extends Model
{
    use HasFactory;
    // TPettyCash.php

protected $fillable = ['invoice_no','date', 'cramount', 'crcode', 'dramount', 'drcode', 'description', 'amount', 'OC', 'BC', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
