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
    </style>
</head>
<body style="margin: 50px;">
    @php
        use Carbon\Carbon;
    @endphp

    @if(Auth::guard('admin')->check())
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

    @else

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

        <div class="row">&nbsp;</div>

        <div class="styleText">
            <strong>Fecha:</strong> {{Carbon::now()->format('Y-m-d')}}<br>
            <strong>Nombre de la compañia:</strong> {{$commerceData->name}}<br>
            <strong>Dirección:</strong> {{$commerceData->address}}<br>
            <strong>Teléfono:</strong> {{$commerceData->phone}}<br>
        </div>

        @if($startDate && $endDate)
            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>

            <strong>Fecha:</strong> {{$startDate}} al {{$endDate}}<br>
        @endif
    </div>
    @endif

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">Fecha</th>
                    <th scope="col">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr>
                    <td>{{Carbon::parse($rate->date)->format('Y-m-d g:i A') }}</td>
                    <td>Bs {{number_format($rate->rate, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>