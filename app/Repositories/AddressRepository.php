<?php

namespace App\Repositories;
use App\Models\Address;

class AddressRepository
{
    public function __construct(
        private Address $address
    ) { }

    public function create(array $addressData): Address
    {
        return $this->address->updateOrCreate([
            'customer_id' => $addressData['customer_id']
        ], $addressData);
    }
}

