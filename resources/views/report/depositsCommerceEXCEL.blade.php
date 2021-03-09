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
    <div class="row">
        <div class="styleText">
            <strong>Fecha:</strong> {{$today}}<br>
            <strong>Nombre de la compañia:</strong> {{$commerceData->name}}<br>
            <strong>Dirección:</strong> {{$commerceData->address}}<br>
            <strong>Teléfono:</strong> {{$commerceData->phone}}<br>
        </div>
    </div>

    <p></p>
    <p></p>

    <strong>Fecha:</strong> {{$startDate}} al {{$endDate}}<br>

    <p></p>
    <p></p>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                    <th scope="col" style="background-color: #585858; color:white; text-align:center;">Total</th>
                    <th scope="col" style="background-color: #585858; color:white; text-align:center;">Estado</th>
                    <th scope="col" style="background-color: #585858; color:white; text-align:center;">Número de Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyDeposits as $history)
                <tr>
                    <td width="12" style="text-align:center;">{{$history->date}}</td>
                    @if($history->status==0)
                        <td class="received" style="color:green; font-weight: bold; text-align:center;">{{$history->total}}</td>
                        <td width="10" class="received" style="color:green; font-weight: bold;text-align:center;">RECIBIDO</td>
                        <td width="25" style="text-align:center;">Transacciones</td>
                    @else 
                        <td class="deposit" style="color:red; font-weight: bold; text-align:center;">{{$history->total}}</td>
                        <td width="10" class="deposit" style="color:red; font-weight: bold; text-align:center;">DEPOSITO</td>
                        <td width="25"  class="depositNumRef" style="text-align:center;">{{$history->numRef}}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>