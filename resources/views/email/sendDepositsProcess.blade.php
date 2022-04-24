<!DOCTYPE html>
<html lang="es">

<head>
    <title>Emailts Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description">
    <meta charset="UTF-8">
</head>

<body>
@php
    use Carbon\Carbon;
@endphp
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
                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">
                        TRANSFERENCIA DE FONDOS EN PROCESO</h2>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href="">
                    <img src="{{ asset('images/email/12.png') }}" alt=""
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                </a>
            </td>
        </tr>
        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">
                    <p style="color:#59595e; margin: 0 0 7px;">Estimado/a <strong>{{strtoupper($user->name)}}</strong></p>
                    <br>
                    <br>
                    <p style="color:#59595e; margin: 0 0 7px;">{{env('APP_NAME')}} informa que los fondos que tenía en su cartera por
                        Concepto de Ventas de Productos y/o Servicios, está en proceso de pago a la cuenta proporcionada por Ud. en el módulo de
                        Bancos.
                    </p>
                    <br>
                    <br>
                    <p style="color:#59595e; margin: 0 0 7px;"> <strong>Fecha:</strong> {{Carbon::now()->format("d/m/Y")}}</p>
                    <p style="color:#59595e; margin: 0 0 7px;"> <strong>Monto:</strong> @if($deposits->coin == 0) $ @else BS @endif {{number_format($deposits->total, 2, ",", ".")}}</p>
                    <br>
                    <br>
                    <p>Nótese que si los fondos fueron transferidos a Bancos Nacionales, estarán disponibles hoy
                        {{Carbon::now()->format("d/m/Y")}} después de la 1:00 pm, en caso de ser Bancos en Panamá o USA, se harán efectivos a
                        más tardar en 48 horas a partir de la recepción de este correo.</p><br><br><br>
                    <br>
                    <br>
                    <h4 style="color:#59595e; margin: 0 0 7px;">Muchas gracias,<br>
                        <br>El equipo de {{env('APP_NAME')}}.
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