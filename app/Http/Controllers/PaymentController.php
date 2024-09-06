<?php

namespace App\Http\Controllers;

use App\Enum\PaymentTypeEnum;
use App\Exceptions\PaymentException;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\PaymentRepository;
use App\Services\CieloAPIService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Validator;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private CustomerRepository $customerRepository,
        private AddressRepository $addressRepository,
        private CieloAPIService $cieloAPIService
    ) {}

    public function createBoleto(Request $request)
    {
        try {

            $validatedData = Validator::validate($request->all(), [
                'customer.name' => 'required|string',
                'customer.identity' => 'required|string|cpf',
                'address.street' => 'required|string|max:24',
                'address.number' => 'required|string|max:5',
                'address.complement' => 'nullable|string|max:14',
                'address.zip_code' => 'required|string|max:9',
                'address.city' => 'required|string|max:20',
                'address.state' => 'required|string|uf',
                'address.country' => 'required|string|size:2',
                'address.district' => 'required|string|max:50',
                'payment.amount' => 'required|integer|min:0'
            ]);
            
            $customer = $this->customerRepository->create($validatedData['customer']);
            $addressData = $validatedData['address'];
            $addressData['customer_id'] = $customer->id;
            $address = $this->addressRepository->create($addressData);
            $paymentData = $validatedData['payment'];
            $paymentData['customer_id'] = $customer->id;
            $paymentData['expire_at'] = Carbon::now()->addDays(3);
            $paymentData['boleto_address'] = 'Av Fictícia 100, Bairro Simulado, Cidade Fake BR';
            $paymentData['provider'] = 'Simulado';
            $paymentData['boleto_instructions'] = 'Pagável em qualquer instituição financeira até a data do vencimento';
            $paymentData['boleto_demonstrative'] = 'Exmplo de demonstrativo';
            $paymentData['type'] = PaymentTypeEnum::BOLETO;
            $payment = $this->paymentRepository->create($paymentData);

            $this->cieloAPIService->createBoleto($customer, $address, $payment);
            
            return response()->json([
                'status' => 'success',
                'content' => $this->paymentRepository->find($payment->id)
            ], 201);

        } catch (ValidationException $ve) {
            return response()->json([
                'status' => 'validation_fail',
                'details' => $ve->errors()
            ], 400);
        } catch (PaymentException $pe) {
            return response()->json([
                'status' => 'error',
                'details' => $pe->getMessage()
            ], $pe->getCode());
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'internal_error',
                'details' => $th->getMessage()
            ], 500);
        }
    }
}
