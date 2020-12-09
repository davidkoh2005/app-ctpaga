<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../../css/styleFormPassword.css">
    <script src="../../js/formPassword.js"></script>
    <script src="../../js/i18n/es.js"></script>
</head>
<body>
    <Section>
        <div class="container">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-left">
                                <div class="col-md-auto col-sm-auto col-auto">
                                    <h5 class="form-changes"> Cambiar Contraseña </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            @if (Session::has('error'))
                                <div class="alert alert-danger">
                                    <strong>Error: </strong> {{Session::get('error') }}
                                </div>
                            @elseif (Session::has('succecs'))
                            <div class="center">
                                <svg width='3em' height='3em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' fill='currentColor'>
                                    <path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
                                </svg>
                                <div class="row">&nbsp;</div>
                                <p>{{Session::get('succecs') }}</p>

                            </div>
                            @else
                            <form id="password-form" class="contact-form" method='POST' action="{{route('form.passwordReset')}}">
                                @csrf
                                <input type="hidden" name="token" id="token" value="{{$token}}">
                                <div class= "form-section current">
                                    <p>Ingrese la nueva contraseña:</p>
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="***************"  required />
                                    <label for="password_confirmation">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="***************"  required />
                                    <div id="errorPassword"></div>
                                </div>

                                <div class="row">&nbsp;</div>

                                <div class="form-navigation bottom">
                                    <button type="submit" class="submit btn btn-bottom">Guardar Contraseña</button>
                                </div>
                                <div class="row justify-content-center" id="loading">
                                    <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                                </div>
                            @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>
</body>
</html>