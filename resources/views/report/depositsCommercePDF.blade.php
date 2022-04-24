<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
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
            position: relative;
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
            <strong>Nombre de la compañia:</strong> {{$commerceData->name}}<br>
            <strong>Dirección:</strong> {{$commerceData->address}}<br>
            <strong>Teléfono:</strong> {{$commerceData->phone}}<br>
        </div>

        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>

        <strong>Fecha:</strong> {{Carbon::parse($startDate)->format('Y-m-d')}} al {{Carbon::parse($endDate)->format('Y-m-d')}}<br>
    </div>

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">Fecha</th>
                    <th scope="col">Total</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Número de Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyDeposits as $history)
                <tr>
                    <td>{{$history->date }}</td>
                    @if($history->status==0)
                        <td class="received">{{$history->total}}</td>
                        <td class="received">RECIBIDO</td>
                        <td class="depositNumRef">Transacciones</td>
                    @else 
                        <td class="deposit">{{$history->total}}</td>
                        <td class="deposit">DEPOSITO</td>
                        <td class="depositNumRef">{{$history->numRef}}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>