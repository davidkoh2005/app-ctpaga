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
            @if($type == 0)
            <td style="border-left: 25px solid #00b426; border-right: 25px solid #00b426; display: flex;
            align-items: center;
            justify-content: center;">
                <div style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: center; font-family: sans-serif;">

                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">
                        BIENVENIDO A CTPAGA
                    </h2>

                </div>
            </td>
            @else
            <td style="border-left: 25px solid red; border-right: 25px solid red; display: flex;
            align-items: center;
            justify-content: center;">
                <div style="color: #34495e; width: 100%; margin: 4% 10% 2%; text-align: center; font-family: sans-serif;">

                    <h2 style="color:#59595e; margin: 0 0 7px; text-transform: uppercase; font-size: 15px; text-align: center;">
                        BIENVENIDO A CTPAGA
                    </h2>

                </div>
            </td>
            @endif
        </tr>

        <tr>
            <td style="background-color: #fff; text-align: left; padding: 0;">
                <br>
                <a href="">
                    @if($type == 0)
                    <img 
                        src="{{ asset('images/email/8.png') }}" 
                        alt="" 
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                    @elseif($type == 1)
                    <img 
                        src="{{ asset('images/email/11.png') }}" 
                        alt="" 
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                    @else
                    <img 
                        src="{{ asset('images/email/10.png') }}" 
                        alt="" 
                        style="padding: 10px; display: block; margin: 0 auto; width: 80px;">
                    @endif
                </a>
            </td>
        </tr>

        <tr>
            <td style="background-color: #fff; font-size: 16px">
                <div style="color: #34495e; margin: 4% 10% 2%; text-align: left; font-family: sans-serif;">

                    <p style="color:#59595e; margin: 0 0 7px;">
                        Estimado/a <strong>{{ strtoupper($commerce->name)}}</strong>
                    </p>

                    <br>
                    <br>

                    @if($type == 0)
                        <p style="color:#59595e; margin: 0 0 7px;">
                            Ctpaga informa que el pago del pedido {{$codeUrl}} fue realizado exitoxamente, tu factura ya se encuentra disponible:
                            <strong>
                            <a href="{{$url}}" target="_blank">{{$url}} </a>
                            </strong> 
                        </p>
                    @elseif($type == 1)
                        <p style="color:#59595e; margin: 0 0 7px;">
                            Ctpaga informa que el pago del pedido {{$codeUrl}} se encuentra en proceso de verificación
                        </p>
                    @else
                        <p style="color:#59595e; margin: 0 0 7px;">
                            Ctpaga informa que el pago del pedido {{$codeUrl}} ha sido cancelado
                        </p>
                    @endif
                    <br>


                    <p style="color:#59595e; margin: 0 0 7px;">Gracias por la confianza depositada en nosotros.</p>
                    <br>
                    <br>
                    <br>
                    <br>

                    <h4 style="color:#59595e; margin: 0 0 7px;">
                        El equipo de Ctpaga.
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