<?php

namespace App\Enum;

enum PaymentTypeEnum: string
{
    const BOLETO = 'boleto';
    const PIX = 'pix';
    const DEBIT = 'debit';
    const CREDIT = 'credit';

}
