<?php

return [
    "services"  => [
        "boleto"            => [
            "service_class"     => App\Services\Payments\BoletoPaymentService::class,
            // Esta é uma simulação com um fake da internet, simulando a geração de um bolero.
            "config"            => [
                "base_url"              => env("BOLETO_SERVICE_BASE_URL"),
                "numero_banco"          => env("BOLETO_SERVICE_BANCO"),
                "local_pagamento"       => env("BOLETO_SERVICE_LOCAL_PAGAMENTO"),
                "cedente"               => env("BOLETO_SERVICE_CEDENTE"),
                "data_documento"        => env("BOLETO_SERVICE_DATA_DOCUMENTO"),
                "numero_documento"      => env("BOLETO_SERVICE_NUMERO_DOCUMENTO"),
                "especie"               => env("BOLETO_SERVICE_ESPECIE"),
                "aceite"                => env("BOLETO_SERVICE_ACEITE"),
                "uso_banco"             => env("BOLETO_SERVICE_USO_BANCO"),
                "carteira"              => 179,
                "especie_moeda"         => "Real",
                "quantidade"            => env("BOLETO_SERVICE_QUANTIDADE"),
                "valor"                 => env("BOLETO_SERVICE_VALOR"),
                "vencimento_em_dias"    => env("BOLETO_SERVICE_VENCIMENTO_EM_DIAS"),
                "agencia"               => env("BOLETO_SERVICE_AGENCIA"),
                "codigo_cedente"        => env("BOLETO_SERVICE_CODIGO_CEDENTE"),
                "meunumero"             => env("BOLETO_SERVICE_MEUNUMERO"),
                "instrucoes"            => env("BOLETO_SERVICE_INSTRUCOES"),
                "mensagem1"             => env("BOLETO_SERVICE_MENSAGEM_1"),
                "mensagem2"             => env("BOLETO_SERVICE_MENSAGEM_2"),
                "mensagem3"             => env("BOLETO_SERVICE_MENSAGEM_3"),
            ]
        ],
        "pix" => [
            "service_class"     => App\Services\Payments\PixPaymentService::class,
            "config"    => [
                "base_url"              => env("PIX_SERVICE_BASE_URL"),
                "numero_banco"          => env("PIX_SERVICE_BANCO"),
                "chave_pix"             => env("PIX_SERVICE_KEY"),
                "chave_de_acesso"       => env("PIX_SERVICE_ACCESS_KEY"),
                "chave_secreta"         => env("PIX_SERVICE_PRIVATE_KEY"),
            ]
        ],
        "credit_card"   => [
            "service_class"     => App\Services\Payments\CreditCardPaymentService::class,
        ]
    ]
];
