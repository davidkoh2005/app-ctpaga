            <!-- ***** Header Start ***** -->
            <header class="navbar navbar-sticky navbar-expand-lg navbar-dark">
                <div class="container position-relative">
                    <a class="navbar-brand" href="{{route('welcome')}}">
                        <img class="navbar-brand-regular" src="{{ asset('images/logo/logoWhite.png').'?v='.time() }}" width="180px" alt="brand-logo">
                        <img class="navbar-brand-sticky" src="{{ asset('images/logo/logoWhite.png').'?v='.time() }}" width="180px" alt="sticky brand-logo">
                    </a>
                    <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-inner">
                        <!--  Mobile Menu Toggler -->
                        <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <nav>
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#home">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#features">Caracteristicas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#pricing">Comisiones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#contact">Contacto</a>
                                </li>
                                @if(Auth::guard('admin')->check())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('admin.dashboard')}}">Ingresar</a>
                                    </li>
                                @elseif(Auth::guard('web')->check())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('commerce.dashboard')}}">Ingresar</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('commerce.login')}}">Iniciar Sesión</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
            <!-- ***** Header End ***** -->