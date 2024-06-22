<?php 

namespace App\Gateways\Payments;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Traits\SaveLogTrait;
use Illuminate\Support\Facades\Http;

class PixGateway implements PaymentGatewayInterface
{
    use SaveLogTrait;
    
    public function pay(array $data) : string
    {
        $this->saveLog("Pix");
        return "Paid with Pix";
    }

    public function getServicePayload() : array
    {
        return [
            "base_url"          => config("payments.services.pix.config.base_url"),
            "numero_banco"      => config("payments.services.pix.config.numero_banco"),
            "chave_pix"         => config("payments.services.pix.config.chave_pix"),
            "chave_de_acesso"   => config("payments.services.pix.config.chave_de_acesso"),
            "chave_secreta"     => config("payments.services.pix.config.chave_secreta")
        ];
    }

    public function paymentDocument(Payment $payment)
    {
        $data = Http::get(config("payments.services.pix.config.base_url"),[
            "data"  => join('::', [
                ...$this->getServicePayload(),
                "valor"     => $payment->amount
            ])
        ]);

        return view("payments.pix", [
            "qr_code"   => $data->body()
        ]);
    }
}
