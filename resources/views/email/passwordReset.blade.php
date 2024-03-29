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
            <td style="border-left: 25px solid #ffa200; border-right: 25px solid #ffa200; display: flex;
            align-items: center;
            justify-content: center;">
                <div style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: center; font-family: sans-serif;">

                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">
                        SOLICITUD RECUPERACIÓN DE <br> CONTRASEÑA DE {{env('APP_NAME')}}
                    </h2>

                </div>
            </td>
        </tr>

        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href="">
                    <img 
                        src="{{ asset('images/email/4.png').'?v='.time() }}" 
                        alt="" 
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                </a>
            </td>
        </tr>

        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">

                    <p style="color:#59595e; margin: 0 0 7px;">
                        Estimado/a <strong>{{strtoupper($user->name)}}</strong>
                    </p>

                    <br>
                    <br>

                    <p style="color:#59595e; margin: 0 0 7px;">
                        Ha solicitado recuperar la contraseña para tu cuenta de {{env('APP_NAME')}}. Por favor seleccione el siguiente
                        enlace 
                        <strong>
                           <a href="{{$url}}">{{$url}} </a>
                        </strong> 
                        para su recuperación
                    </p>

                    <br>

                    <p style="color:#59595e; margin: 0 0 7px;">
                        Recuerde que su contraseña debe contener los siguientes caracteres:

                        <ul>
                            <li>Una letra en Mayúscula</li>
                            <li>Al menos una letra en minúscula</li>
                            <li>Al menos un carácter numérico</li>
                            <li>Un carácter especial (#, ., $, /, *)</li>
                        </ul>

                        <br>
                        <br>

                        Si no solicitó este cambio, comuníquese con el servicio de atención al cliente a través del correo 
                       <strong>soporte@{{env('DOMAIN_EMAIL')}}</strong>.

                       <br>
                       <br>

                       Gracias por elegir {{env('APP_NAME')}} como su plataforma de comercio electrónico.
                    </p>

                    <br>
                    <br>
                    <br>
                    <br>

                    <h4 style="color:#59595e; margin: 0 0 7px;">
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