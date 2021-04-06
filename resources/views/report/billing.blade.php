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
        <table cellspacing="0" cellpadding="0" style="border: none;" width="100%">
            <tr>
                <td>
                    <strong>Nombre: </strong> {{$paid->nameClient == 'Tienda Web'? 'Cliente' : ucwords($paid->nameClient)}}<br>
                    <strong>Correo: </strong> {{$paid->email}}<br><br>
                </td>
                <td>
                    <strong>Numero de pedido: </strong> {{$paid->codeUrl}}<br>
                    <strong>Fecha de pedido: </strong> {{Carbon::parse($paid->date)->format('Y-m-d')}}<br>
                    <strong>Método de pago: </strong> 
                        @if($paid->nameCompanyPayments == 'Square' || $paid->nameCompanyPayments == 'E-sitef')
                            Tarjeta de Crédito
                        @elseif($paid->nameCompanyPayments == 'Pago en Efectivo')
                            Efectivo
                        @else
                            {{ucfirst($paid->nameCompanyPayments)}}
                        @endif
                        <br> 
                </td>
            </tr>
        </table>

        <div class="row">
            <strong>Detalle de envio </strong> <br>
            <strong>Nombre: </strong> {{ucwords($paid->nameShipping)}}<br>
            <strong>Teléfono: </strong> {{$paid->numberShipping}}<br>
            <strong>Dirección: </strong> {{ucfirst($paid->addressShipping)}}<br>
            @if($paid->detailsShipping)
                <strong>Detalle: </strong> {{ucfirst($paid->addressShipping)}}<br>
            @endif  
        </div>
        
    </div>

    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <div style="text-align:center"> <h2>Pedido</h2> </div>

    <div class="row">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">Catindad</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{$sale->quantity }}</td>
                    <td>{{$sale->name }}</td>
                    <td>@php showPrice($sale->price, $sale->rate, $sale->coin, $paid->coinClient); @endphp</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">&nbsp;</div>

        <div style="text-align:right;"> <strong>Descuento (%):</strong> {{$paid->percentage}} </div> 
        <div style="text-align:right;"> <strong>Envío:</strong>@if($paid->coin == 0) $ @else BS @endif {{number_format($paid->priceShipping, 2, ',', '.')}} </div>
        <div style="text-align:right;"> <strong>Total:</strong> @if($paid->coin == 0) $ @else BS @endif {{number_format($paid->total, 2, ',', '.')}} </div>
    </div>

    @php
        function showPrice($price, $rate, $coin, $coinClient){
            if ($price == "FREE")
                echo "GRATIS";
            else if ($coinClient == 0)
                echo "$ ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
            else
                echo "Bs ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        }

        function exchangeRate($price, $rate, $coin, $coinClient){
            $result = 0;

            if($coin == 0 && $coinClient == 1)
                $result = (floatval($price) * $rate);
            else if($coin == 1 && $coinClient == 0)
                $result = (floatval($price) / $rate);
            else
                $result = (floatval($price));

            return $result;
        }
    @endphp
</body>
</html>