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
                    <p style="color:#59595e; margin: 0 0 7px;">Estimado/a <strong>{{ $paid->nameClient == 'Tienda Web'? 'Cliente' : strtoupper($paid->nameClient)}}</strong></p>
                    <br>
                    <br>
                    <p style="color:#59595e; margin: 0 0 7px;">CTpaga Delivery ha retirado del comercio {{$commerce->name}} el pedido {{$paid->codeUrl}}, apenas sea
                        entregado con Éxito en la dirección {{$paid->addressShipping}},
                        le llegará otro correo con la Confirmación de Entrega.</p>
                    <p style="text-align: center">[Pedido {{$paid->codeUrl}}] (
                        @php
                            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                            $fecha = Carbon::parse($paid->date);
                            $mes = $meses[($fecha->format('n')) - 1];
                        @endphp
                        {{$fecha->format('d') . ' ' . $mes . ' ' . $fecha->format('Y')}})</p>
                    
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