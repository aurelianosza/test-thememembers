<?php 

namespace App\Services\Payments;

use App\Services\Payments\Interfaces\PaymentInterface;
use App\Services\Payments\Interfaces\CanBePaydInterface;
use App\Services\Payments\Mail\MailSentBoletoDocument;
use App\Traits\SaveLogTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BoletoPaymentService implements PaymentInterface
{
    public function pay(CanBePaydInterface $payment) : void
    {
        $payment
            ->logPayment("Request to pay {$payment->paymentData()["payment_hash"]} with boleto.");

        Mail::to($payment->paymentData()["email"])
            ->queue(new MailSentBoletoDocument($payment));
    }

    public function getServicePayload() : array
    {
        return [
            "numero_banco"          => config("payments.services.boleto.config.numero_banco"),
            "local_pagamento"       => config("payments.services.boleto.config.local_pagamento"),
            "cedente"               => config("payments.services.boleto.config.cedente"),
            "data_documento"        => config("payments.services.boleto.config.data_documento"),
            "numero_documento"      => config("payments.services.boleto.config.numero_documento"),
            "especie"               => config("payments.services.boleto.config.especie"),
            "aceite"                => config("payments.services.boleto.config.aceite"),
            "uso_banco"             => config("payments.services.boleto.config.uso_banco"),
            "carteira"              => config("payments.services.boleto.config.carteira"),
            "especie_moeda"         => config("payments.services.boleto.config.especie_moeda"),
            "quantidade"            => config("payments.services.boleto.config.quantidade"),
            "valor"                 => config("payments.services.boleto.config.valor"),
            "agencia"               => config("payments.services.boleto.config.agencia"),
            "codigo_cedente"        => config("payments.services.boleto.config.codigo_cedente"),
            "meunumero"             => config("payments.services.boleto.config.meunumero"),
            "instrucoes"            => config("payments.services.boleto.config.instrucoes"),
            "mensagem1"             => config("payments.services.boleto.config.mensagem1"),
            "mensagem2"             => config("payments.services.boleto.config.mensagem2"),
            "mensagem3"             => config("payments.services.boleto.config.mensagem3"),
        ];
    }

    public function paymentDocument(CanBePaydInterface $payment)
    {
        if($payment->paymentData()["status"] == "success")
        {
            return view("payments.boleto-allready-paid");
        }

        $data = Http::withQueryParameters([
            ...$this->getServicePayload(),
            "valor_documento"       => $payment->paymentData()["amount"],
            "data_processamento"    => $payment->paymentData()["processment_date"],
            "vencimento"            => Carbon::today()->add("days", config("payments.boleto.config.vencimento_em_dias"))->format("Y-m-d"),

        ])
        ->get(config("payments.services.boleto.config.base_url"));

        return $data->body();
    }
}
