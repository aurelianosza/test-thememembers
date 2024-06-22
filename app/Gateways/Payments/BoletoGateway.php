<?php 

namespace App\Gateways\Payments;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\PaymentLog;
use App\Traits\SaveLogTrait;
use Illuminate\Support\Facades\Http;

class BoletoGateway implements PaymentGatewayInterface
{
    use SaveLogTrait;

    public function pay(array $data) : string
    {
        $this->saveLog("Boleto");
        return "Payment with Boleto";
    }

    public function paymentDocument()
    {

        // http://www.sicadi.com.br/mhouse/boleto/boleto3.php?numero_banco=341-7&local_pagamento=PAG%C1VEL+EM+QUALQUER+BANCO+AT%C9+O+VENCIMENTO&cedente=Microhouse+Inform%E1tica+S%2FC+Ltda&data_documento=21%2F06%2F2024&numero_documento=DF+00113&especie=&aceite=N&data_processamento=21%2F06%2F2024&uso_banco=&carteira=179&especie_moeda=Real&quantidade=&valor=&vencimento=21%2F06%2F2024&agencia=0049&codigo_cedente=10201-5&meunumero=00010435&valor_documento=260%2C00&instrucoes=Taxa+de+visita+de+suporte%0D%0AAp%F3s+o+vencimento+R%24+0%2C80+ao+dia&mensagem1=&mensagem2=&mensagem3=ATEN%C7%C3O%3A+N%C3O+RECEBER+AP%D3S+15+DIAS+DO+VENCIMENTO&sacado=&Submit=Enviar

        $data = Http::withQueryParameters([
            "banco"                 => "",
            "local_pagamento"       => "",
            "cedente"               => "",
            "data_documento"        => "",
            "numero_documento"      => "",
            "especie"               => "",
            "aceite"                => "",
            "data_processamento"    => "",
            "uso_banco"             => "",
            "carteira"              => 179,
            "especie_moeda"         => "Real",
            "quantidade"            => "",
            "valor"                 => "",
            "vencimento"            => "",
            "agencia"               => "",
            "codigo_cedente"        => "",
            "meunumero"             => "",
            "valor_documento"       => "",
            "instrucoes"            => "",
            "mensagem1"             => "",
            "mensagem2"             => "",
            "mensagem3"             => "",
        ])
        ->get("https://api.qrserver.com/v1/create-qr-code/");

        return $data->body();
    }
}
