<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cambiar Contraseña</title>
    @include('bookshop')
    <script src="{{ asset('js/formPassword.js') }}"></script>
    <script src="{{ asset('js/i18n/es.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('landingPage/css/style.css') }}">
</head>

<body class="homepage-5 accounts">
    <!--====== Scroll To Top Area Start ======-->
    <div id="scrollUp" title="Scroll To Top">
        <i class="fas fa-arrow-up"></i>
    </div>
    <!--====== Scroll To Top Area End ======-->

    <div class="main">
        @include('navbar')

        <!-- ***** Welcome Area Start ***** -->
        <section id="home" class="section welcome-area bg-overlay d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <!-- Welcome Intro Start -->
                    <div class="col-12 col-lg-7">
                        <div class="welcome-intro">
                            <h1 class="text-white">Bienvenido a Ctpaga!</h1>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-5">
                        <!-- Contact Box -->
                        <div class="contact-box bg-white text-center rounded p-4 p-sm-5 mt-5 mt-lg-0 shadow-lg">
                            @if (Session::has('error'))
                                <div class="alert alert-danger">
                                    <strong>Error: </strong> {{Session::get('error') }}
                                </div>
                            @elseif (Session::has('succecs'))
                            <div class="center">
                                <svg width='3em' height='3em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' fill='currentColor' style="color: #00cc5f;">
                                    <path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
                                </svg>
                                <div class="row">&nbsp;</div>
                                <p>{{Session::get('succecs') }}</p>

                            </div>
                            @else
                                @if($type == 0)
                                    <form id="password-form" class="contact-form" method='POST' action="{{route('form.passwordReset')}}">
                                @else
                                    <form id="password-form" class="contact-form" method='POST' action="{{route('form.passwordResetDelivery')}}">
                                @endif
                                @csrf
                                <input type="hidden" name="token" id="token" value="{{$token}}">
                                <div class="contact-top">
                                    <h3 class="contact-title">Cambiar Contraseña</h3>
                                    <h5 class="text-secondary fw-3 py-3">Complete todos los campos necesarios.</h5>
                                </div>
                                <div class="row">
                                    <div class="col-12 form-section current">
                                        <div class="form-group">
                                            <div class="input-group">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                              </div>
                                              <input type="password" class="form-control" name="password" id="password" placeholder="Nueva Contraseña" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                                              </div>
                                              <input type="password" class="form-control"  name="password_confirmation" id="password_confirmation" placeholder="Confirmar Contraseña" required="required">
                                            </div>
                                        </div>
                                        <div id="errorPassword" style="color:red;"></div>
                                    </div>
                                    <div class="col-12 form-navigation">
                                        <button class="submit btn btn-bordered w-100 mt-3 mt-sm-4" type="submit">Guardar Contraseña</button>
                                        <div id="loading">
                                            <img class="justify-content-center" src="{{ asset('images/loadingTransparent.gif') }}" style="max-width:80px !important;">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape-bottom">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="shape-fill" fill="#FFFFFF" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7  c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4  c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
        </section>
        <!-- ***** Welcome Area End ***** -->
    </div>


    <!-- ***** All jQuery Plugins ***** -->

    <!-- Bootstrap js -->
    <script src="{{ asset('landingPage/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('landingPage/js/bootstrap/bootstrap.min.js') }}"></script>

    <!-- Plugins js -->
    <script src="{{ asset('landingPage/js/plugins/plugins.min.js') }}"></script>

    <!-- Active js -->
</body>

</html>