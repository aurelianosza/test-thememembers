<!DOCTYPE html>
<html
  lang="pt-BR"
>

<head>
  <title>Boleto para pagamento</title>
</head>

<body style="margin: 0">
  <span style="margin: 0 auto; 
    display: block;
    padding: 20px;
    background-color: #f9f9f9;
    max-width: 620px;
    width: 620px;">
    <table style="background: #f8f8f8 !important; 
      color: #484b4d;
      font-family: Roboto, Arial, Helvetica, sans-serif;
      display: block;
      border-radius: 10px;" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td style="padding: 0">
            <!-- Header  -->
            <span style="background-color: #f5f5f8 !important;
              padding: 20px 24px;
              display: block;
              border-radius: 10px 10px 0px 0px;">
              <img src="{{ asset('public/mlogo.jpeg') }}" width="90px" style="object-fit: contain" />
            </span>

            <!-- Contents  -->
            <ul style="background-color: #fff !important;
              padding: 40px 20px;
              list-style: none;
              border-radius: 8px;
              margin: 0;">
              <a
                href="{{ route('payments.document', [$payment->paymentData()['payment_hash']]) }}"
              >
                Clique aqui
              </a> e faça o download do documento para pagamento.
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </span>
</body>

</html>
