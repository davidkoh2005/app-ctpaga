<!DOCTYPE html>
<html lang="es">

<head>
    <title>Emailts Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description">
    <meta charset="UTF-8">
</head>
@php
    use Carbon\Carbon;

    $subTotal = 0;

    function showPrice($price, $rate, $coin, $coinClient){
        if ($price == "FREE")
            echo "GRATIS";
        else if ($coinClient == 0){
            echo "$ ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        }else{
            echo "Bs ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        }
    }

    function exchangeRate($price, $rate, $coin, $coinClient){
        global $subTotal;
        $result = 0;

        if($coin == 0 && $coinClient == 1)
            $result = (floatval($price) * $rate);
        else if($coin == 1 && $coinClient == 0)
            $result = (floatval($price) / $rate);
        else
            $result = (floatval($price));
            
        $subTotal = $subTotal + $result; 
        return $result;
    }

    function subTotal($price, $rate, $coin, $coinClient){
        if ($coinClient == 0){
            echo "$ ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        }else{
            echo "Bs ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        }
    }
@endphp
<body>
    <table style="width: 100%; padding: 10px; margin: 0 auto; border-collapse: collapse;">
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <div style="width: 100%; height: 40px; background: #E4E4E4;">
                </div>
                <br>
            </td>
        </tr>
        <tr>
            @include('email.header')
        </tr>
        <tr>
            <td
                style="border-left: 25px solid #00b426; border-right: 25px solid #00b426; display: flex; align-items: center; justify-content: center;">
                <div
                    style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: center; font-family: sans-serif;">
                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">CONFIRMACIÓN DE RETIRO DE <br> PRODUCTOS PARA SU ENTREGA</h2>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href="">
                    <img src="{{ asset('images/email/7.png') }}" alt=""
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;"></a>
            </td>
        </tr>
        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">
                    <p style="color:#59595e; margin: 0 0 7px;">Estimado/a <strong>{{ $paid->nameClient != 'Tienda Web'?? strtoupper($paid->nameClient)}}</strong></p>
                    <br>
                    <br>
                    <p style="color:#59595e; margin: 0 0 7px;">Nuestro equipo de delivery/entregas ha retirado del
                        comercio {{$commerce->name}}  @if (count($sales) == 1) el Producto @else los Productos @endif correspondientes al pedido {{$paid->codeUrl}}, apenas sea
                        entregado con Éxito en su destino {{$paid->addressShipping}},
                        le llegará otro correo con la Confirmación de Entrega.</p>
                    <p style="text-align: center">[Pedido {{$paid->codeUrl}}] (
                        @php
                            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                            $fecha = Carbon::parse($paid->date);
                            $mes = $meses[($fecha->format('n')) - 1];
                        @endphp
                        {{$fecha->format('d') . ' ' . $mes . ' ' . $fecha->format('Y')}})</p>
                    <table style="margin: 30px auto; border: 1px solid #59595e;">
                        <thead>
                            <tr>
                                <td style="padding: 20px 40px">Producto </td>
                                <td style="padding: 20px 40px">Cantidad</td>
                                <td style="padding: 20px 40px">Precio </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td style="padding: 20px 40px">{{$sale->name}}</td>
                                <td style="padding: 20px 40px">{{$sale->quantity}}</td>
                                <td style="padding: 20px 40px">@php showPrice($sale->price, $sale->rate, $sale->coin, $sale->coinClient); @endphp</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table style="margin: 30px auto; border: 1px solid #59595e;">
                        <thead>
                            <tr>
                                <td style="padding: 20px 40px">Subtotal</td>
                                <td style="padding: 20px 40px">
                                @php
                                    foreach($sales as $sale){
                                        $subTotal = exchangeRate($sale->price, $sale->rate, $sale->coin, $sale->coinClient);
                                    }
                                @endphp
                                @if($paid->coinClient==0) $ @else BS  @endif {{number_format($subTotal, 2, ',', '.')}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 40px">Envío</td>
                                <td style="padding: 20px 40px">{{$paid->selectShipping}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 40px">Precio Envío</td>
                                <td style="padding: 20px 40px">@if($paid->coinClient==0) $ @else BS @endif {{number_format($paid->priceShipping, 2, ',', '.')}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 40px">Método de pago</td>
                                <td style="padding: 20px 40px">{{ $paid->nameCompanyPayments == 'Square' || $paid->nameCompanyPayments == 'E-sitef'? 'Pago con Tarjeta ' : $paid->nameCompanyPayments == 'Pago en Efectivo'? 'Pago en Efectivo' : 'Pago con'.$paid->nameCompanyPayments}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 40px">Total</td>
                                <td style="padding: 20px 40px">@if($paid->coin == 0) $ @else BS @endif {{number_format($paid->total, 2, ',', '.')}}</td>
                            </tr>
                        </thead>
                    </table><br>
                    <p style="color:#59595e; margin: 0 0 7px;">Gracias por la confianza depositada en nosotros.</p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <h4 style="color:#59595e; margin: 0 0 7px;">
                        <br>El equipo de CTpaga.
                    </h4>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            @include('email.socials')
        </tr>
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <br>
                <div style="width: 100%; height: 40px; background: #E4E4E4;">
                </div>
            </td>
        </tr>
    </table>
</body>

</html>