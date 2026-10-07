<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'Code', 'Title', 'Gender', 'Name', 'First_name', 'Middle_name', 'Last_name',
        'Address_1', 'City_1', 'Address_2', 'City_2', 'Contact_1', 'Contact_2',
        'Email', 'NIC', 'Driving_license', 'Passport', 'Other_identifications',
        'Status', 'BC', 'OC', 'credit_limit',
    ];

    public function getCurrentBalance(): float
    {
        $sums = TCusSaleTrance::where('customer', $this->NIC)
            ->selectRaw('SUM(cr_amount) as cr, SUM(dr_amount) as dr')
            ->first();

        return (float) (($sums->cr ?? 0) - ($sums->dr ?? 0));
    }

    public function wouldExceedCreditLimit(float $additionalCreditAmount): bool
    {
        if ($this->credit_limit === null) {
            return false;
        }

        return ($this->getCurrentBalance() + $additionalCreditAmount) > (float) $this->credit_limit;
    }
}
