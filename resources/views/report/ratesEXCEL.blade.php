<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg') }}" />
    <style>
        .styleText {
            position: relative;
            right: 40px;
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
    @php
        use Carbon\Carbon;
    @endphp

    @if(Auth::guard('admin')->check())
    <p></p>
    <p></p>
    <p></p>
    <p></p>

    @else
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <div class="row">
        <div class="styleText">
            <strong>Fecha: </strong> {{Carbon::now()->format('Y-m-d')}}<br>
            <strong>Nombre de la compañia: </strong> {{$commerceData->name}}<br>
            <strong>Dirección: </strong> {{$commerceData->address}}<br>
            <strong>Teléfono: </strong> {{$commerceData->phone}}<br>
        </div>
    </div>
    @endif
    
    @if($startDate && $endDate)
    <p></p>
    <p></p>

    <strong>Fecha:</strong> {{$startDate}} al {{$endDate}}<br>
    @endif
    <p></p>
    <p></p>

    <div class="row">
        <table id="table_Rate" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col" width="30" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                    <th scope="col" width="30" style="background-color: #585858; color:white; text-align:center;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr>
                    <td style="text-align:center;">{{Carbon::parse($rate->date)->format('Y-m-d g:i A') }}</td>
                    <td style="text-align:center;">Bs {{number_format($rate->rate, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>