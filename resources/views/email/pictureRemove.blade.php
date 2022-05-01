<!DOCTYPE html>
<html lang="es">

<head>
    <title>Emailts Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description">
    <meta charset="UTF-8">
</head>

<body>
    <table style="width: 100%; padding: 10px; margin: 0 auto; border-collapse: collapse;">
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <div style="width: 100%; height: 40px; background: #E4E4E4;"></div><br>
            </td>
        </tr>
        <tr>
            @include('email.header')
        </tr>
        <tr>
            <td
                style="border-left: 25px solid red; border-right: 25px solid red; display: flex; align-items: center; justify-content: center;">
                <div
                    style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: center; font-family: sans-serif;">
                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align:center">DOCUMENTOS
                        RECHAZADOS EN <br> SU PUNTO DE VENTA VIRTUAL</h2>
                </div>
            </td>
        </tr>
        </tr>
        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href=""><img src="{{ asset('images/email/9.png').'?v='.time() }}" alt=""
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;"></a>
            </td>
        </tr>
        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">
                    <p style="color:#59595e; margin: 0 0 7px;">Estimado/a <strong>{{strtoupper($commerce->name)}}</strong></p><br><br>
                    <p style="color:#59595e; margin: 0 0 7px;">Lamentamos notificarle que los documentos cargados en el
                        sistema no cumplen con las políticas exigidas por las autoridades reguladoras.<br>
                        <br>Le invitamos a que vuelva a cargar nuevos documentos que cumplan con los requerimientos.
                    </p><br><br><br>
                    <p style="color:#59595e; margin: 0 0 7px;">Gracias por elegir {{env('APP_NAME')}}.</p>
                    <h4 style="color:#59595e; margin: 0;">El equipo de {{env('APP_NAME')}}.</h4><br><br><br><br>
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