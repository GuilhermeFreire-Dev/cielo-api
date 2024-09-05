<?php

namespace App\Utils;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Payment;

class CieloPayloadMaker
{
    public static function createPayloadFromBoleto(
        Customer $customer,
        Address $address,
        Payment $payment
    ) {
        return [
            'MerchantOrderId' => strval($payment->id),
            'Customer' => [
                'Name' => $customer->name,
                'Identity' => $customer->identity,
                'Address' => [
                    'Street' => $address->street,
                    'Number' => $address->number,
                    'Complement' => $address->complement,
                    'ZipCode' => $address->zip_code,
                    'District' => $address->district,
                    'City' => $address->city,
                    'State' => $address->state,
                    'Country' => $address->country
                ],
                'Billing' => [
                    'Street' => $address->street,
                    'Number' => $address->number,
                    'Complement' => $address->complement,
                    'Neighborhood' => $address->district,
                    'City' => $address->city,
                    'State' => $address->state,
                    'Country' => $address->country,
                    'ZipCode' => $address->zip_code
                ]
            ],
            'Payment' => [
                'Type' => ucfirst($payment->type),
                'Amount' => $payment->amount,
                'Provider' => $payment->provider,
                'Address' => $payment->boleto_address,
                'BoletoNumber' => str_pad(strval($payment->id), 9, '0', STR_PAD_LEFT),
                'Assignor' => 'Empresa Teste',
                'Demonstrative' => $payment->boleto_demonstrative,
                'ExpirationDate' => $payment->expire_at->toISOString(),
                'Identification' => $customer->identity,
                'Instructions' => $payment->boleto_instructions
            ]
        ];
    }
}