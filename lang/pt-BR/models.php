<?php

return [
    "App\Models\Product"    => [
        "name"                  => "produto"
    ],
    "App\Models\Buyer"      => [
        "name"                  => "comprador"
    ],
    "App\Models\Payment"    => [
        "name"                  => "pagamento",
        "statuses"              => [
            "pending"               => "pendente",
            "error"                 => "erro de pagamento",
            "success"               => "sucesso",
        ],
        "messages"              => [
            "payment_request"       => "Requisição de pagamento feita com sucesso."
        ]
    ]
];
