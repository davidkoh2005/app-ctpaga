    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{route('admin.dashboard')}}">Ctpaga</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdmin" arial-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarAdmin">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.balance')}}">Balance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.commerces')}}">Comerciante</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.transactions')}}">Transacciones</a>
            </li>
        </ul>
        <form class="form-inline justify-content-between" action="{{route('admin.logout')}}">
            <button class="btn btn-light" type="submit">Salir</button>
        </form>
    </nav>