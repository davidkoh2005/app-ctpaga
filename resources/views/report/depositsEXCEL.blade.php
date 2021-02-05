<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg') }}" />
    <style>
        .styleText {
            font-family: 'Montserrat-Bold', sans-serif;
            color: black;
        }

    </style>
</head>
<body style="margin: 50px;">
    <div class="row">
        <div class="styleText">
            <strong>Fecha:</strong> {{$today}}<br>
            <strong>Direccion:</strong> Los Dos Caminos.<br>
            <strong>Teléfono:</strong> 0212-555-5555<br>
        </div>
    </div>

    <p></p>
    <p></p>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Nombre Compañia</th>
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                    <th scope="col" width="10" style="background-color: #585858; color:white; text-align:center;">Moneda</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Total</th>
                    <th scope="col" width="25" style="background-color: #585858; color:white; text-align:center;">Numero Referencia</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBs=0; $totalUSD=0; @endphp
                @foreach($deposits ?? '' as $deposit)
                <tr>
                    <td style="text-align:center;">{{ $deposit->name }}</td>
                    <td style="text-align:center;">{{$deposit->date}}</td>
                    <td style="text-align:center;">@if($deposit->coin == 0 )  USD @else Bs @endif</td>
                    <td style="text-align:center;">@if($deposit->coin == 0 )  $ @else Bs @endif {{ $deposit->total }}</td>
                    <td style="text-align:center;">{{ $deposit->numRef }}</td>
                    <td style="text-align:center; color:green;">Completado</td>
                    @php 
                        if($deposit->coin == 0 )
                            $totalUSD += $deposit->total;
                        else
                            $totalBs += $deposit->total;
                    @endphp
                </tr>
                @endforeach
            </tbody>
        </table>
        <p></p>
        <p></p>
        <div class="styleText"  style="margin: 20px; text-align: right;">
            <strong>Total USA:</strong> $ {{$totalUSD}} <br>
            <strong>Total VE:</strong> Bs {{$totalBs}}<br>
        </div>
    </div>
</body>
</html>