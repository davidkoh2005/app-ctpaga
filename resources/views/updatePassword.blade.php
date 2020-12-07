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
                            @if (Session::has('message'))
                                <div class="alert alert-danger">
                                    <strong>Error: </strong> {{Session::get('message') }}
                                </div>
                            @else
                            <form id="password-form" class="contact-form" method='POST' action="{{route('form.formSubmit')}}">
                                @csrf
                                <input type="hidden" name="token" id="token" value="{{$token}}">
                                <div class= "form-section">
                                    <p>Ingrese la nueva contraseña:</p>
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" class="form-control" placeholder="***************" min="6" data-parsley-min="6" required />
                                    <label for="passwordConfirm">Confirmar contraseña</label>
                                    <input type="password" name="passwordConfirm" class="form-control" placeholder="***************" min="6" data-parsley-min="6" required />
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