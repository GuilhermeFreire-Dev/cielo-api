<?php

namespace App\Repositories;
use App\Models\Customer;


class CustomerRepository 
{
    public function __construct(
        private Customer $customer
    ) { }

    public function create(array $customerData): Customer
    {
        return $this->customer->firstOrCreate([
            'identity' => $customerData['identity']
        ], $customerData);
    }
}
