<?php 

namespace App\Gateways\Payments;

use App\Interfaces\PaymentGatewayInterface;
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

    public function paymentDocument()
    {
        $data = Http::get("http://api.qrserver.com/v1/create-qr-code/",[
            "data"  => "algum nome",
        ]);

        return $data->body();
    }
}
