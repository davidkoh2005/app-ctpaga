<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg') }}" />
    <style>
        .styleText {
            font-family: 'Montserrat-Bold', sans-serif;
            color: black;
        }

        .positionImage {
            position: absolute;
            right: 40px;
            top: 0px;
        }

        #table_id {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table_id td, #table_id th {
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table_id th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #585858;
            color: white;
        }
    </style>
</head>
<body style="margin: 50px;">
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <div class="row">
        <div class="styleText">
            <strong>Fecha:</strong> {{$today}}<br>
            <strong>Nombre de la compañia:</strong> {{$commerce->name}}<br>
            <strong>Dirección:</strong> {{$commerce->address}}<br>
            <strong>Teléfono:</strong> {{$commerce->phone}}<br>
        </div>
    </div>
    
    @if($startDate && $endDate)
    <p></p>
    <p></p>

    <strong>Fecha:</strong> {{$startDate}} al {{$endDate}}<br>
    @endif
    <p></p>
    <p></p>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Nombre Cliente</th>
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Código</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Total</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Pago</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Estado</th>
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td style="text-align:center;">{{ $transaction->nameClient}}</td>
                    <td style="text-align:center;">{{ $transaction->codeUrl}}</td>
                    <td style="text-align:center;">@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                    <td style="text-align:center;">@if($transaction->nameCompanyPayments == "PayPal" || $transaction->nameCompanyPayments == "E-Sitef" ) Tienda Web @else {{$transaction->nameCompanyPayments}} @endif</td>
                    <td>
                        @if($transaction->statusPayment == 0)
                            <div class="cancelled">Cancelado</div>
                        @elseif($transaction->statusPayment == 1)
                            <div class="pending">Pendiente</div>
                        @else
                            <div class="completed">Completado</div>
                        @endif
                    </td>
                    <td style="text-align:center;"> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>