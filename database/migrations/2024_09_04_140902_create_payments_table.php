<?php

use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->on('customers')->references('id');
            $table->string('provider');
            $table->string('boleto_address');
            $table->string('boleto_bar_code')->nullable();
            $table->string('boleto_digitable_line')->nullable();
            $table->string('boleto_number')->nullable();
            $table->string('boleto_instructions');
            $table->string('boleto_demonstrative')->nullable();
            $table->string('url')->nullable();
            $table->string('acquirer_id')->nullable();
            $table->integer('amount')->default(0);
            $table->enum('type', [
                PaymentTypeEnum::BOLETO,
                PaymentTypeEnum::PIX,
                PaymentTypeEnum::DEBIT,
                PaymentTypeEnum::CREDIT
            ]);
            $table->dateTime('expire_at')->nullable();
            $table->enum('status', [
                PaymentStatusEnum::CREATED,
                PaymentStatusEnum::AUTHORIZED,
                PaymentStatusEnum::COMPLETED,
                PaymentStatusEnum::CANCELED,
                PaymentStatusEnum::DENIED,
                PaymentStatusEnum::REFUNDED
            ])->default(PaymentStatusEnum::CREATED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};


/*
 "Payment":
    {
        "Type":"Boleto",
        "Amount":15700,
        "Provider":"Bradesco2",
        "Address": "Rua Teste",
        "BoletoNumber": "123",
        "Assignor": "Empresa Teste",
        "Demonstrative": "Desmonstrative Teste",
        "ExpirationDate": "2020-12-31",
        "Identification": "11884926754",
        "Instructions": "Aceitar somente até a data de vencimento, após essa data juros de 1% dia."
    }
}
    
"Payment":
    {
        "Instructions": "Aceitar somente até a data de vencimento, após essa data juros de 1% dia.",
        "ExpirationDate": "2015-01-05",
        "Url": "https://apisandbox.cieloecommerce.cielo.com.br/post/pagador/reenvia.asp/a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51",
        "Number": "123-2",
        "BarCodeNumber": "00096629900000157000494250000000012300656560",
        "DigitableLine": "00090.49420 50000.000013 23006.565602 6 62990000015700",
        "Assignor": "Empresa Teste",
        "Address": "Rua Teste",
        "Identification": "11884926754",
        "PaymentId": "a5f3181d-c2e2-4df9-a5b4-d8f6edf6bd51",
        "Type": "Boleto",
        "Amount": 15700,
        "Currency": "BRL",
        "Country": "BRA",
        "Provider": "Bradesco2",
        "ExtraDataCollection": [],
        "Status": 1,
        "Links": [
            {
                "Method": "GET",
                "Rel": "self",
                "Href": "https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales/{PaymentId}"
            }
        ]
    }


*/