<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

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
            <td style="border-left: 25px solid #ffa200; border-right: 25px solid #ffa200; display: flex; align-items: center; justify-content: center;">
                <div style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">

                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">
                        LE DAMOS LA BIENVENIDA A <br> {{env('APP_NAME')}} SU PUNTO DE VENTA
                    </h2>

                </div>
            </td>
        </tr>

        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href="">
                    <img src="{{ asset('images/email/1.png').'?v='.time() }}" style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                </a>
            </td>
        </tr>

        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">

                    <p style="color:#59595e; margin: 0 0 7px;"> Estimado/a <strong>{{strtoupper($user->name)}}</strong></p>

                    <br>
                    <br>

                    <p style="color:#59595e; margin: 0 0 7px;">
                        En {{env('APP_NAME')}} estamos encantados de que haya creado su cuenta.
                        <br>
                        <br>
                        Su nombre de usuario es <strong><a href="">{{$user->email}}</a></strong>
                    </p>

                    <br>

                    <p style="color:#59595e; margin: 0 0 7px;">
                       Ya puede empezar a usar la App en su totalidad, pero lo invitamos a 
                       completar los datos en el Menú BANCO para poder recibir los fondos procesados.
                    </p>

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                    <h4 style="color:#59595e; margin: 0 0 7px;">
                        Muchas gracias,
                        <br>
                        <br>
                        El equipo de {{env('APP_NAME')}}.
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