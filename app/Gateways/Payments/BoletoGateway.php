<?php 

namespace App\Gateways\Payments;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;
use App\Traits\SaveLogTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BoletoGateway implements PaymentGatewayInterface
{
    use SaveLogTrait;

    public function pay(array $data) : string
    {
        $this->saveLog("Boleto");
        return "Payment with Boleto";
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

    public function paymentDocument(Payment $payment)
    {
        $data = Http::withQueryParameters([
            ...$this->getServicePayload(),
            "valor_documento"       => $payment->amount,
            "data_processamento"    => $payment->created_at->format('Y-m-d'),
            "vencimento"            => Carbon::today()->add("days", config("payments.boleto.config.vencimento_em_dias"))->format("Y-m-d"),

        ])
        ->get(config("payments.services.boleto.config.base_url"));

        return $data->body();
    }
}
