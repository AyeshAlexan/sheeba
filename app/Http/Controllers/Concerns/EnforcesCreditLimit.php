<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Customer;

/**
 * Blocks a new sales invoice's unpaid/credit portion from pushing a
 * customer's outstanding balance past their configured credit limit.
 * A null credit_limit means unlimited (no check) — opt-in per customer.
 */
trait EnforcesCreditLimit
{
    protected function guardCreditLimit(?string $customerNic, float $creditAmount)
    {
        if ($creditAmount <= 0 || empty($customerNic)) {
            return null;
        }

        $customer = Customer::where('NIC', $customerNic)->first();

        if (!$customer || $customer->credit_limit === null) {
            return null;
        }

        if ($customer->wouldExceedCreditLimit($creditAmount)) {
            return back()->withInput()->with(
                'error',
                "This invoice would exceed {$customer->Name}'s credit limit of {$customer->credit_limit}."
            );
        }

        return null;
    }
}
