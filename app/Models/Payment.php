<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'provider',
        'boleto_address',
        'boleto_bar_code',
        'boleto_digitable_line',
        'boleto_number',
        'boleto_instructions',
        'boleto_demonstrative',
        'url',
        'acquirer_id',
        'amount',
        'type',
        'expire_at',
        'status'
    ];
}
