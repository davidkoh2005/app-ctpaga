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
    @php
        use Carbon\Carbon;
    @endphp
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
    
    <div class="styleText">
        <strong>Fecha:</strong> {{Carbon::now()->format('Y-m-d')}}<br>
    </div>

    @if($startDate && $endDate)
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>

        <strong>Fecha:</strong> {{Carbon::parse($startDate)->format('Y-m-d')}} al {{Carbon::parse($endDate)->format('Y-m-d')}}<br>
    @endif

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">#</th>
                    <th scope="col">Nombre Delivery</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                <tr>
                    <td>{{ $history->id }}</td>
                    <td>{{ $history->name }}</td>
                    <td>${{ number_format($history->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>