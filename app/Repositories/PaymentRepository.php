<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository 
{
    public function __construct(
        private Payment $payment
    ) { }

    public function create(array $paymentData): Payment
    {
        return $this->payment->create($paymentData);
    }

    public function update(array $paymentData, int $id): bool
    {
        return $this->payment->where('id', $id)->update($paymentData);
    }

    public function find(int $id): Payment
    {
        return $this->payment->where('id', $id)->get()->first();
    }
}
