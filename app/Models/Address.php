<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'street',
        'number',
        'complement',
        'zip_code',
        'city',
        'state',
        'country',
        'district'
    ];
}
