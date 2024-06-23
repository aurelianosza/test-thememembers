<!DOCTYPE html>
<html lang="pt-BR">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1">
    <title>QR Code PIX</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        #qrcode {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>QR Code pague via PIX</h1>
    <div id="qrcode">
        <img src="data:image/png;base64,{{ base64_encode($qr_code) }}" alt="QR Code PIX">
    </div>
    <div>
        Situação de pagamento : {{ $payment->getStatusName() }}
    </div>
</body>
</html>
