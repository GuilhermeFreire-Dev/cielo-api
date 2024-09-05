<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    const CREATED = 'created';
    const AUTHORIZED = 'authorized';
    const COMPLETED = 'completed';
    const DENIED = 'denied';
    const CANCELED = 'canceled';
    const REFUNDED = 'refunded';

}
