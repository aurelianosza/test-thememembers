<?php

return [
    'gateways' => [
        'boleto'            => App\Gateways\Payments\BoletoGateway::class,
        'pix'               => App\Gateways\Payments\PixGateway::class,
        'credit_card'       => App\Gateways\Payments\CreditCardGateway::class,
    ],
];
