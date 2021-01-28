<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../../images/logo/logoct.svg" />
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
            <strong>Direccion:</strong> Los Dos Caminos.<br>
            <strong>Telefono:</strong> 0212-555-5555<br>
        </div>

        <div class="positionImage">
        @php
            $path = public_path('/images/logo/logo.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
            <img src="{{$base64}}" width="140px">
        </div>
    </div>

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div class="col-12">
        @if($idCommerce > 0)
        <label for="" class="nameCompany"><strong>Nombre de Compañia:</strong> {{ $companyName}} </label>
        @endif
    
        <div class="tableShow">
            <table id="table_id" class="table table-bordered mb-5 display">
                <thead>
                    <tr class="table-title">
                        @if($idCommerce == 0)<th scope="col">Nombre Compañia</th>@endif
                        <th scope="col">Nombre Cliente</th>
                        <th scope="col">Total</th>
                        <th scope="col">Pago</th>
                        <th scope="col">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalBs=0; $totalUSD=0; @endphp
                    @foreach($transactions ?? '' as $transaction)
                    <tr>
                        @if($idCommerce == 0)<td>{{ $transaction->name }}</td>@endif
                        <td>{{ $transaction->nameClient}}</td>
                        <td>@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                        <td> {{$transaction->nameCompanyPayments}}</td>
                        <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                    </tr>
                    @php 
                    if($transaction->coin == 0 )
                        $totalUSD += floatval($transaction->total);
                    else
                        $totalBs += floatval($transaction->total);
                    @endphp
                    @endforeach
                </tbody>
            </table>
            <div class="styleText"  style="margin: 20px; text-align: right;">
                <strong>Total USA:</strong> $ {{$totalUSD}} <br>
                <strong>Total VE:</strong> Bs {{$totalBs}}<br>
            </div>
        </div>
    </div>
</body>
</html>