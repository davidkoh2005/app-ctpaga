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
                            <div class="row justify-content-center">
                                <div class="col-md-auto col-sm-auto col-auto">
                                    <img src="../images/logo/logo.png" alt="image" width="160px" height="90px">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-danger">
                                    <strong>Error: </strong> {{Session::get('message') }}
                                </div>
                            @endif
                            <form class="contact-form" method='POST' action="{{route('form.login')}}">
                                @csrf
                                <div class= "form-section">
                                    <label for="email">Correo</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="joedoe@gmail.com"  required />
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="***************"  required />
                                    <div id="errorPassword"></div>
                                </div>

                                <div class="row">&nbsp;</div>

                                <div class="form-navigation bottom">
                                    <button type="submit" class="submit btn btn-bottom">Ingresar</button>
                                </div>
                                <div class="row justify-content-center" id="loading">
                                    <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>

</body>
</html>