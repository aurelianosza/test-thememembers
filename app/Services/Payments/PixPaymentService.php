<?php 

namespace App\Services\Payments;

use App\Services\Payments\Exceptions\PaymentErrorException;
use App\Services\Payments\Interfaces\CanBePaydInterface;
use App\Services\Payments\Interfaces\PaymentInterface;
use Illuminate\Support\Facades\Http;

class PixPaymentService implements PaymentInterface
{
    public function pay(CanBePaydInterface $payment) : void
    {
        $payment
            ->logPayment("Request to pay {$payment->paymentData()["payment_hash"]} with PIX.");
        
        sleep(rand(5, 30));

        // Nesta área estarei simulando um número aletório para erro de pagamento via pix
        if(rand(0, 5) == 5)
        {
            $payment
                ->setStatus("error");

            $payment
                ->logPayment("Payment {$payment->paymentData()["payment_hash"]} fail with PIX.");

            throw new PaymentErrorException();
        }

        $payment
            ->setStatus("success");

        $payment
            ->logPayment("Payment {$payment->paymentData()["payment_hash"]} success with PIX.");
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

    public function paymentDocument(CanBePaydInterface $payment)
    {
        $data = Http::get(config("payments.services.pix.config.base_url"),[
            "data"  => join('::', [
                ...$this->getServicePayload(),
                "valor"     => $payment->paymentData()["amount"]
            ])
        ]);

        return view("payments.pix", [
            "payment"   => $payment,
            "qr_code"   => $data->body()
        ]);
    }
}
