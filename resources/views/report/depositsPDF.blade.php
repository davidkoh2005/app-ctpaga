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

        .completed {
            border-radius: 5px;
            background-color: #00cc5f;
            color: white
        }
    </style>
</head>
<body style="margin: 50px;">
    <div class="row">
        <div class="positionImage">
        @php
            $path = public_path('/images/logo/logo.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
            <img src="{{$base64}}" width="240px">
        </div>
    </div>

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">Nombre Compañia</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Moneda</th>
                    <th scope="col">Total</th>
                    <th scope="col">Numero Referencia</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBs=0; $totalUSD=0; @endphp
                @foreach($deposits ?? '' as $deposit)
                <tr>
                    <td>{{ $deposit->name }}</td>
                    <td>{{$deposit->date}}</td>
                    <td>@if($deposit->coin == 0 )  USD @else Bs @endif</td>
                    <td>@if($deposit->coin == 0 )  $ @else Bs @endif {{ $deposit->total }}</td>
                    <td>{{ $deposit->numRef }}</td>
                    <td><div class="completed">Completado</div></td>
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
        <div class="styleText"  style="margin: 20px; text-align: right;">
            <strong>Total USA:</strong> $ {{$totalUSD}} <br>
            <strong>Total VE:</strong> Bs {{$totalBs}}<br>
        </div>
    </div>
</body>
</html>