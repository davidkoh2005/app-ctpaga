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
            position: absolute;
            right: 40px;
            top: 0px;
        }

        .positionImage {
            left: 40px;
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

        .received {
            color: green;
            font-weight: bold;
        }

        .deposit {
            color: red;
            font-weight: bold;
        }

        .depositNumRef {
            color: black;
            font-weight: bold;
        }

        .cancelled {
            padding-left: 5px;
            padding-right: 5px;
            border-radius: 5px;
            background-color: #585858;
            color: white
        }

        .pending  {
            padding-left: 5px;
            padding-right: 5px;
            border-radius: 5px;
            background-color: red;
            color: white
        }

        .completed {
            padding-left: 5px;
            padding-right: 5px;
            border-radius: 5px;
            background-color: #00cc5f;
        }
    </style>
</head>
<body style="margin: 50px;">
    <div class="row">
        <div class="positionImage">
        @php
            if($pictureUser)
                $path = public_path($pictureUser->url);
            else
                $path = public_path('/images/perfilUser.png');

            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
            <img src="{{$base64}}" width="100px">
        </div>

        <div class="styleText">
            <strong>Fecha:</strong> {{$today}}<br>
            <strong>Nombre de la compañia:</strong> {{$commerce->name}}<br>
            <strong>Dirección:</strong> {{$commerce->address}}<br>
            <strong>Teléfono:</strong> {{$commerce->phone}}<br>
        </div>

        @if($startDate && $endDate)
            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>

            <strong>Fecha:</strong> {{$startDate}} al {{$endDate}}<br>
        @endif
    </div>

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">Nombre Cliente</th>
                    <th scope="col">Código</th>
                    <th scope="col">Total</th>
                    <th scope="col">Pago</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->nameClient }}</td>
                    <td>{{ $transaction->codeUrl }}</td>
                    <td>@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                    <td>@if($transaction->nameCompanyPayments == "PayPal" || $transaction->nameCompanyPayments == "E-Sitef" ) Tienda Web @else {{$transaction->nameCompanyPayments}} @endif</td>
                    <td>
                        @if($transaction->statusPayment == 0)
                            <div class="cancelled">Cancelado</div>
                        @elseif($transaction->statusPayment == 1)
                            <div class="pending">Pendiente</div>
                        @else
                            <div class="completed">Completado</div>
                        @endif
                    </td>
                    <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>