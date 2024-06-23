@php

use Illuminate\Support\Carbon;

@endphp


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1">
    <title>Comprovante de Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .receipt {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .receipt h1 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
        }
        .receipt p {
            margin: 5px 0;
        }
        .receipt .amount {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .receipt .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Comprovante de Pagamento</h1>
        <p><strong>Data:</strong> {{ Carbon::create($payment->paymentData()["processment_date"])->format('d/m/Y') }}</p>
        <p><strong>Hora:</strong> {{ Carbon::create($payment->paymentData()["processment_date"])->format('H:i:s') }}</p>
        <p><strong>Cartão:</strong> **** **** **** 1234</p>
        <p><strong>Nome:</strong> {{ $payment->buyer->name }} </p>
        <p><strong>Descrição:</strong> Compra de Eletrônicos</p>
        <div class="amount">
            {{ moneyFormat($payment->paymentData()["amount"]) }}
        </div>
        <p><strong>Situação de pagamento:</strong> {{ $payment->getStatusName() }} </p>
        <p><strong>Autenticação:</strong> {{ $payment->paymentData()["payment_hash"] }} </p>
        <div class="footer">
            Obrigado por sua compra!
        </div>
    </div>
</body>
</html>
