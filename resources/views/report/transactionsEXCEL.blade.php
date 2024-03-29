<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg').'?v='.time() }}" />
    <style>
        .styleText {
            font-family: 'Montserrat-Bold', sans-serif;
            color: black;
        }
    </style>
</head>
<body style="margin: 50px;">
    <p></p>
    <p></p>
    <p></p>
    <p></p>

    @if($idCommerce > 0)
        <p></p>
        <p></p>
        <label for="" class="nameCompany"><strong>Nombre de Compañia:</strong> {{ $companyName}} </label>
    @endif

    <p></p>
    <p></p>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    @if($idCommerce == 0)<th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Nombre Compañia</th>@endif
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Nombre Cliente</th>
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Código</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Total</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Pago</th>
                    <th scope="col" width="15" style="background-color: #585858; color:white; text-align:center;">Estado</th>
                    <th scope="col" width="20" style="background-color: #585858; color:white; text-align:center;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBs=0; $totalUSD=0; @endphp
                @foreach($transactions ?? '' as $transaction)
                <tr>
                    @if($idCommerce == 0)<td style="text-align:center;">{{ $transaction->name }}</td>@endif
                    <td style="text-align:center;">{{ $transaction->nameClient }}</td>
                    <td style="text-align:center;">{{ $transaction->codeUrl }}</td>
                    <td style="text-align:center;">@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                    <td style="text-align:center;"> {{$transaction->nameCompanyPayments}}</td>
                    <td style="text-align:center;">
                        @if($transaction->statusPayment == 0)
                            <div class="cancelled">Cancelado</div>
                        @elseif($transaction->statusPayment == 1)
                            <div class="pending">Pendiente</div>
                        @else
                            <div class="completed">Completado</div>
                        @endif
                    </td>
                    <td style="text-align:center;"> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
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
        <p></p>
        <p></p>
        <div class="styleText"  style="margin: 20px; text-align: right;">
            <strong>Total USA:</strong> $ {{$totalUSD}} <br>
            <strong>Total VE:</strong> Bs {{$totalBs}}<br>
        </div>
    </div>
</body>
</html>