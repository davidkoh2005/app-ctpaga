<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>ctpaga</title>
        <!-- ***** All CSS Files 
        ***** -->

        <!-- Style css -->
        <link rel="stylesheet" type="text/css" href="../landingPage/css/style.css">
    
    </head>
    <body>

        <!--====== Scroll To Top Area Start ======-->
        <div id="scrollUp" title="Scroll To Top">
            <i class="fas fa-arrow-up"></i>
        </div>
        <!--====== Scroll To Top Area End ======-->

        <div class="main">
            @include('navbarMain')

            <!-- ***** Welcome Area Start ***** -->
            <section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Welcome Intro Start -->
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="welcome-intro">
                                <h1 class="text-white">Ctpaga</h1>
                                <p class="text-white my-4">Permite pagar productos y/o servicios desde la comodidad de tu casa.</p>
                                <!-- Store Buttons -->
                                <div class="button-group store-buttons d-flex">
                                    <a href="#">
                                        <img src="../landingPage/img/icon/google-play.png" alt="">
                                    </a>
                                    <a href="#">
                                        <img src="../landingPage/img/icon/app-store.png" alt="">
                                    </a>
                                </div>
                                <span class="d-inline-block text-white fw-3 font-italic mt-3">* Disponible en iPhone, iPad y todos los dispositivos Android</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <!-- Welcome Thumb -->
                            <div class="welcome-thumb mx-auto" data-aos="fade-left" data-aos-delay="500" data-aos-duration="1000">
                                <img class="img-mobile" src="../images/mobile.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Shape Bottom -->
                <div class="shape-bottom">
                    <svg viewBox="0 0 1920 310" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="svg replaced-svg">
                        <title>sApp Shape</title>
                        <desc>Created with Sketch</desc>
                        <defs></defs>
                        <g id="sApp-Landing-Page" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="sApp-v1.0" transform="translate(0.000000, -554.000000)" fill="#FFFFFF">
                                <path d="M-3,551 C186.257589,757.321118 319.044414,856.322454 395.360475,848.004007 C509.834566,835.526337 561.525143,796.329212 637.731734,765.961549 C713.938325,735.593886 816.980646,681.910577 1035.72208,733.065469 C1254.46351,784.220361 1511.54925,678.92359 1539.40808,662.398665 C1567.2669,645.87374 1660.9143,591.478574 1773.19378,597.641868 C1848.04677,601.75073 1901.75645,588.357675 1934.32284,557.462704 L1934.32284,863.183395 L-3,863.183395" id="sApp-v1.0"></path>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>
            <!-- ***** Welcome Area End ***** -->

            <!-- ***** Counter Area Start ***** -->
            <section class="section counter-area ptb_50">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-5 col-sm-3 single-counter text-center">
                            <div class="counter-inner p-3 p-md-0">
                                <!-- Counter Item -->
                                <div class="counter-item d-inline-block mb-3">
                                    <span class="counter fw-7">10</span><span class="fw-7">M</span>
                                </div>
                                <h5>Usuario</h5>
                            </div>
                        </div>
                        <div class="col-5 col-sm-3 single-counter text-center">
                            <div class="counter-inner p-3 p-md-0">
                                <!-- Counter Item -->
                                <div class="counter-item d-inline-block mb-3">
                                    <span class="counter fw-7">23</span><span class="fw-7">K</span>
                                </div>
                                <h5>Descarga</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Counter Area End ***** -->

            <!-- ***** Features Area Start ***** -->
            <section id="features" class="section features-area style-two overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2>Lo que hace diferente a Ctpaga?</h2>
                                <p class="d-none d-sm-block mt-4">Permite realizar venta de productos sin necesidad de tener gastos adicional para crear pagina web.</p>
                                <p class="d-block d-sm-none mt-4">Ctpaga es una aplicación para vender productos en las redes sociales.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 res-margin">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInLeft" data-wow-delay="0.4s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="../landingPage/img/icon/featured-img/layers.png" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Completamente funcional</h3>
                                    <p>El propósito de Ctpaga es permitir vender los productos a través de las redes sociales sin necesidad de gastos adicional de manera gratuitas.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 res-margin">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInUp" data-wow-delay="0.2s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="../landingPage/img/icon/featured-img/speak.png" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Chat con el Soporte</h3>
                                    <p>Puede contactar con el soporte por medio de Whatsapp para aclarar dudas o problemas.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInRight" data-wow-delay="0.4s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="../landingPage/img/icon/featured-img/lock.png" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Datos Seguros</h3>
                                    <p>Ctpaga garantiza que su cuenta y su información personal estén seguras.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Features Area End ***** -->

            <!-- ***** Service Area Start ***** -->
            <section class="section service-area bg-gray overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-12 col-lg-6 order-2 order-lg-1">
                            <!-- Service Text -->
                            <div class="service-text pt-4 pt-lg-0">
                                <h2 class="text-capitalize mb-4">Inicia el negocio de tus sueños en segundos</h2>
                                <!-- Service List -->
                                <ul class="service-list">
                                    <!-- Single Service -->
                                    <li class="single-service media py-2">
                                        <div class="service-icon pr-4">
                                            <span><i class="fas fa-arrow-circle-down"></i></span>
                                        </div>
                                        <div class="service-text media-body">
                                            <p><br>Descarga la app.</p>
                                        </div>
                                    </li>
                                    <!-- Single Service -->
                                    <li class="single-service media py-2">
                                        <div class="service-icon pr-4">
                                            <span><i class="fas fa-pen-square"></i></span>
                                        </div>
                                        <div class="service-text media-body">
                                            <p><br>Rellena los datos de la empresa y bancaria.</p>
                                        </div>
                                    </li>
                                    <!-- Single Service -->
                                    <li class="single-service media py-2">
                                        <div class="service-icon pr-4">
                                            <span><i class="fas fa-cart-plus"></i></span>
                                        </div>
                                        <div class="service-text media-body">
                                            <p><br>Agrega tus productos.</p>
                                        </div>
                                    </li>
                                    <!-- Single Service -->
                                    <li class="single-service media py-2">
                                        <div class="service-icon pr-4">
                                            <span><i class="fas fa-money-bill"></i></span>
                                        </div>
                                        <div class="service-text media-body">
                                            <p><br>Recibe pagos.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 order-1 order-lg-2 d-none d-lg-block">
                            <!-- Service Thumb -->
                            <div class="service-thumb mx-auto">
                                <img src="../images/perfil.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Service Area End ***** -->

            <!-- ***** Discover Area Start ***** -->
            <section class="section discover-area overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-12 col-lg-6 order-2 order-lg-1">
                            <!-- Discover Thumb -->
                            <div class="service-thumb discover-thumb mx-auto pt-5 pt-lg-0">
                                <img src="../images/ws.png" alt="">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 order-1 order-lg-2">
                            <!-- Discover Text -->
                            <div class="discover-text pt-4 pt-lg-0">
                                <h2 class="pb-4 pb-sm-0">Vende por redes sociales.</h2>
                                <p class="d-none d-sm-block pt-3 pb-4">Crea y comparte enlaces de pago con tus clientes por el medio donde te comunicas con ellos:.</p>
                                <!-- Check List -->
                                <ul class="check-list">
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">Whatsapp.</span>
                                        </div>
                                    </li>
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">Instagram.</span>
                                        </div>
                                    </li>
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">Twitter.</span>
                                        </div>
                                    </li>
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">Facebook.</span>
                                        </div>
                                    </li>
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">Correo Electrónico.</span>
                                        </div>
                                    </li>
                                    <li class="py-1">
                                        <!-- List Box -->
                                        <div class="list-box media">
                                            <span class="icon align-self-center"><i class="fas fa-check"></i></span>
                                            <span class="media-body pl-3">SMS.</span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="icon-box d-flex justify-content-center">
                                    <img src="../images/redes.png" alt="image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Discover Area End ***** -->

            <!-- ***** Work Area Start ***** -->
            <section class="section work-area bg-overlay overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Work Content -->
                            <div class="work-content text-center">
                                <h2 class="text-white">Cómo funciona Ctpaga?</h2>
                                <p class="d-none d-sm-block text-white my-3 mt-sm-4 mb-sm-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.</p>
                                <p class="d-block d-sm-none text-white my-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="../landingPage/img/icon/work/download.png" alt="">
                                </div>
                                <h3 class="text-white py-3">Instala la aplicación</h3>
                                <p class="text-white">Disponible en iPhone, iPad y todos los dispositivos Android</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="../landingPage/img/icon/work/settings.png" alt="">
                                </div>
                                <h3 class="text-white py-3">Configurar tu cuenta</h3>
                                <p class="text-white">Rellena los datos de la empresa, bancaria y Agrega tus productos</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="../landingPage/img/icon/work/app.png" alt="">
                                </div>
                                <h3 class="text-white py-3">Disfruta las funciones!</h3>
                                <p class="text-white">Envia enlance a través de las redes sociales y recibe pagos en Dolares y Bolivares</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Work Area End ***** -->

            <!-- ***** Price Plan Area Start ***** -->
            <section id="pricing" class="section price-plan-area bg-gray overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2>Comisiones de retiro de dinero</h2>
                                <p class="d-none d-sm-block mt-4">El retiro de moneda estadounidense (Dolares) solo puede recibir dinero a través de cuenta Estados Unidos o Panamá.</p>
                                <p class="d-block d-sm-none mt-4">El retiro de moneda Venezolano (Bolivares) solo puede recibir dinero a través de cuenta Venezolano.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-10 col-lg-8">
                            <div class="row price-plan-wrapper">
                                <div class="col-12 col-md-6">
                                    <!-- Single Price Plan -->
                                    <div class="single-price-plan text-center p-5 wow fadeInLeft" data-aos-duration="2s" data-wow-delay="0.4s">
                                        <!-- Plan Thumb -->
                                        <div class="plan-thumb">
                                            <img class="avatar-lg" src="../images/eeuu.png" alt="">
                                        </div>
                                        <!-- Plan Title -->
                                        <div class="plan-title my-2 my-sm-3">
                                            <h4 class="text-uppercase">Dolares</h4>
                                        </div>
                                        <!-- Plan Price -->
                                        <div class="plan-price pb-2 pb-sm-3">
                                            <h2 class="fw-7">5<small class="fw-6">%</small> <small class="fw-6">+</small> 0.50 <small class="fw-6">$</small></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0">
                                    <!-- Single Price Plan -->
                                    <div class="single-price-plan text-center p-5 wow fadeInRight" data-aos-duration="2s" data-wow-delay="0.4s">
                                        <!-- Plan Thumb -->
                                        <div class="plan-thumb">
                                            <img class="avatar-lg" src="../images/venezuela.png" alt="">
                                        </div>
                                        <!-- Plan Title -->
                                        <div class="plan-title my-2 my-sm-3">
                                            <h4 class="text-uppercase">Bolivares</h4>
                                        </div>
                                        <!-- Plan Price -->
                                        <div class="plan-price pb-2 pb-sm-3">
                                            <h2 class="fw-7">5<small class="fw-6">%</small> <small class="fw-6">+</small> 0.50 <small class="fw-6">$</small></h2>
                                            <h4 class="text-uppercase">(Al cambio del dia)</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Price Plan Area End ***** -->

            <!-- ***** FAQ Area Start ***** -->
            <section id="faq" class="section faq-area style-two ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2 class="text-capitalize">Preguntas frecuentes</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <!-- FAQ Content -->
                            <div class="faq-content">
                                <!-- sApp Accordion -->
                                <div class="accordion" id="sApp-accordion">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <!-- Single Card -->
                                            <div class="card border-0">
                                                <!-- Card Header -->
                                                <div class="card-header bg-inherit border-0 p-0">
                                                    <h2 class="mb-0">
                                                        <button class="btn px-0 py-3" type="button">
                                                            Cómo instalar Ctpaga?
                                                        </button>
                                                    </h2>
                                                </div>
                                                <!-- Card Body -->
                                                <div class="card-body px-0 py-3">
                                                 Abre Play Store o App Store. y busca WhatsApp. Toca INSTALAR.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <!-- Single Card -->
                                            <div class="card border-0">
                                                <!-- Card Header -->
                                                <div class="card-header bg-inherit border-0 p-0">
                                                    <h2 class="mb-0">
                                                        <button class="btn px-0 py-3" type="button">
                                                         ¿Cómo puedo editar mi información personal?
                                                        </button>
                                                    </h2>
                                                </div>
                                                <!-- Card Body -->
                                                <div class="card-body px-0 py-3">
                                                    Ingrese en nuestra aplicación Ctpaga selecciona Menú (<i class="fa fa-bars" aria-hidden="true"></i>) y luego Perfil, nos aparecera para cambiar datos personales, empresa y bancaria.
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <p class="text-body text-center pt-4 px-3 fw-5">No he encontrado una respuesta adecuada? <a href="#contact">Contactar via WhatsApp.</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** FAQ Area End ***** -->

            <!-- ***** Download Area Start ***** -->
            <section class="section download-area overlay-dark ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-9">
                            <!-- Download Text -->
                            <div class="download-text text-center">
                                <h2 class="text-white">Ctpaga está disponible para todos los dispositivos</h2>
                                
                                <!-- Store Buttons -->
                                <div class="button-group store-buttons d-flex justify-content-center">
                                    <a href="#">
                                        <img src="../landingPage/img/icon/google-play.png" alt="">
                                    </a>
                                    <a href="#">
                                        <img src="../landingPage/img/icon/app-store.png" alt="">
                                    </a>
                                </div>
                                <span class="d-inline-block text-white fw-3 font-italic mt-3">* Disponible en iPhone, iPad y todos los dispositivos Android</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Download Area End ***** -->


            <!--====== Contact Area Start ======-->
            <section id="contact" class="contact-area bg-gray ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2 class="text-capitalize">Contacto</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-5">
                            <!-- Contact Us -->
                            <div class="contact-us">
                                <p class="mb-3">Con el objeto de brindar atención a los usuarios en tiempo real, ponemos a su disposición la línea:</p>
                                <ul>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <span class="media-body align-self-center">Vestibulum nulla libero, convallis, tincidunt suscipit diam, DC 2002</span>
                                        </a>
                                    </li>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <span class="media-body align-self-center">+1 230 456 789-012 345 6789</span>
                                        </a>
                                    </li>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="media-body align-self-center">exampledomain@domain.com</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pt-4 pt-md-0">
                            <!-- Contact Box -->
                            <div class="contact-box text-center">
                                <!--Google map-->
                                <div class="mapouter">
                                    <div class="gmap_canvas">
                                        <iframe width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=caracas&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--====== Contact Area End ======-->

            <!--====== Height Emulator Area Start ======-->
            <div class="height-emulator d-none d-lg-block"></div>
            <!--====== Height Emulator Area End ======-->

            <!--====== Footer Area Start ======-->
            <footer class="footer-area footer-fixed">
                <!-- Footer Top -->
                <div class="footer-top ptb_100">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Logo -->
                                    <a class="navbar-brand" href="#">
                                        <img class="logo" src="../images/logo/logo.png" alt="">
                                    </a>
                                    <p class="mt-2 mb-3">Comparte tus aplicaciones móviles favoritas con tus amigos.</p>
                                    <!-- Social Icons -->
                                    <div class="social-icons d-flex">
                                        <a class="facebook" href="#">
                                            <i class="fab fa-facebook-f"></i>
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a class="twitter" href="#">
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a class="google-plus" href="#">
                                            <i class="fab fa-google-plus-g"></i>
                                            <i class="fab fa-google-plus-g"></i>
                                        </a>
                                        <a class="vine" href="#">
                                            <i class="fab fa-vine"></i>
                                            <i class="fab fa-vine"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Enlaces útiles</h3>
                                    <ul>
                                        <li class="py-2"><a href="#home">Inicio</a></li>
                                        <li class="py-2"><a href="#features">Caracteristicas</a></li>
                                        <li class="py-2"><a href="#pricing">Comisiones</a></li>
                                        <li class="py-2"><a href="#faq">Preguntas frecuentes</a></li>
                                        <li class="py-2"><a href="#contact">Contacto</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Descarga</h3>
                                    <!-- Store Buttons -->
                                    <div class="button-group store-buttons store-black d-flex flex-wrap">
                                        <a href="#">
                                            <img src="../landingPage/img/icon/google-play-black.png" alt="">
                                        </a>
                                        <a href="#">
                                            <img src="../landingPage/img/icon/app-store-black.png" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <!-- Copyright Area -->
                                <div class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
                                    <!-- Copyright Left -->
                                    <div class="copyright-left">&copy; Copyrights 2020 Ctpaga Todos los derechos reservados.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!--====== Footer Area End ======-->
        </div>
        <!-- ***** All jQuery Plugins ***** -->
        <!-- jQuery(necessary for all JavaScript plugins) -->
        <script src="../landingPage/js/jquery/jquery.min.js"></script>

        <!-- Bootstrap js -->
        <script src="../landingPage/js/bootstrap/popper.min.js"></script>
        <script src="../landingPage/js/bootstrap/bootstrap.min.js"></script>
        <!-- Bootstrap js -->
        <script src="../landingPage/js/bootstrap/popper.min.js"></script>

        <!-- Plugins js -->
        <script src="../landingPage/js/plugins/plugins.min.js"></script>

        <!-- Active js -->
        <script src="../landingPage/js/active.js"></script>
    </body>

</html>
