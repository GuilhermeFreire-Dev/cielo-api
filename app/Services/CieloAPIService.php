<?php

namespace App\Services;

use App\Enum\PaymentStatusEnum;
use App\Exceptions\PaymentException;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Utils\CieloPayloadMaker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CieloAPIService
{
    public function __construct(
        private Client $httpClient,
        private PaymentRepository $paymentRepository,
        private array $headers = [],
        private string $endpoint = '',
    ) { 
        $this->headers = [
            'MerchantId' => env('CIELO_MERCHANT_ID'),
            'MerchantKey' => env('CIELO_MERCHANT_KEY'),
            'Content-Type' => 'application/json'
        ];
        $this->endpoint = env('CIELO_ENDPOINT');
    }

    public function createBoleto(
        Customer $customer,
        Address $address,
        Payment $payment
    ) {

        $payload = CieloPayloadMaker::createPayloadFromBoleto($customer, $address, $payment);

        try {

            $request = $this->httpClient->post("{$this->endpoint}/sales", [
                'body' => json_encode($payload),
                'headers' => $this->headers
            ]);

            $response = json_decode($request->getBody()->getContents());

            $paymentData = [
                'boleto_bar_code' => $response->Payment->BarCodeNumber,
                'boleto_digitable_line' => $response->Payment->DigitableLine,
                'boleto_number' => $response->Payment->BoletoNumber,
                'url' => $response->Payment->Url,
                'acquirer_id' => $response->Payment->PaymentId,
                'status' => $response->Payment->Status == 1 ? 
                    PaymentStatusEnum::AUTHORIZED : PaymentStatusEnum::DENIED
            ];
            
            $this->paymentRepository->update($paymentData, $payment->id);

        } catch (ClientException $ce) {
            $response = json_decode($ce->getResponse()->getBody()->getContents());
            throw new PaymentException($response[0]->Message, $ce->getCode());
        } catch (\Throwable $th) {
            throw new PaymentException($th->getMessage(), 500);
        }
    }
}