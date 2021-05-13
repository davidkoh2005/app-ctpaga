<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
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

    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>

    <div class="row">
        <div class="styleText">
            <strong>Fecha: </strong> {{Carbon::now()->format('Y-m-d')}}<br>
        </div>
    </div>
    
    @if($startDate && $endDate)
    <p></p>
    <p></p>

    <strong>Fecha:</strong> {{Carbon::parse($startDate)->format('Y-m-d')}} al {{Carbon::parse($endDate)->format('Y-m-d')}}<br>
    @endif
    <p></p>
    <p></p>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col" width="10" style="background-color: #585858; color:white; text-align:center;">#</th>
                    <th scope="col" width="30" style="background-color: #585858; color:white; text-align:center;">Nombre Delivery</th>
                    <th scope="col" width="30" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                    <th scope="col" width="30" style="background-color: #585858; color:white; text-align:center;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                <tr>
                    <td style="text-align:center;">{{ $history->id }}</td>
                    <td style="text-align:center;">{{ $history->name }}</td>
                    <td style="text-align:center;">{{ Carbon::parse($history->date)->format('Y-m-d h:i A') }}</td>
                    <td style="text-align:center;">${{ number_format($history->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>