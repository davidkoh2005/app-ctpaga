<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../../css/show.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
    <script type="text/javascript" src="../../js/show.js"></script>
    <script type="text/javascript" src="../../js/rotate.js"></script>
    <script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">Ctpaga</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.dashboard')}}">Principal</a>
                </li>
            </ul>
        </div>
        <form class="d-flex" action="{{route('admin.logout')}}">
            <button class="btn btn-light" type="submit">Salir</button>
        </form>
    </div>
    </nav>
    <section>
        <div class="row">
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-header">
                        Selfie
                    </div>
                    <div class="card-body center zoom">
                        <img src="{{url($profile->url)}}" width="250px" height="350px">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-header">
                        Informacion
                    </div>
                    <div class="card-body">
                        <h5 class="center">Datos Personal</h5>
                        <label><strong>Nombre: </strong>{{$user->name}}</label> <br>
                        <label><strong>Telefono: </strong>{{$user->phone}}</label> <br>
                        <label><strong>Dirección: </strong>{{$user->address}}</label> <br>
                        <label><strong>Correo: </strong>{{$user->email}}</label> 

                        <div class="row">&nbsp;</div>

                        <h5 class="center">Datos de Empresa</h5>
                        <label><strong>Nombre: </strong>{{$commerce->name}}</label> <br>
                        <label><strong>Rif: </strong>{{$commerce->rif}}</label> <br>
                        <label><strong>Telefono: </strong>{{$commerce->phone}}</label> <br>
                        <label><strong>Dirección: </strong>{{$commerce->address}}</label> <br>
                        <label><strong>Link: </strong><a href="http://{{$domain}}/{{$commerce->userUrl}}" class="tienda">Tienda</a></label> <br>

                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-header">
                        Balance
                    </div>
                    <div class="card-body ">
                        <h1 class="center"> @if($balance->coin == 0) $ @else BS @endif {{$balance->total}} </h1>
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <input type="button" class="btn pay btn-bottom btn-current" value="Pagar">
                        <div class="row">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($pictures as $picture)
                <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        @if($picture->descripction == 'Identification')
                            Identificación
                        @else
                            {{$picture->description}}
                        @endif
                    </div>
                    <div class="card-body center zoom">
                        <img src="{{url($picture->url)}}" width="250px" height="350px">
                    </div>
                </div>
                </div>
            @endforeach
        </div>
    </section>


    <!--- Modal -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <img src="" class="imagepreview" >
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <button type="button" id="left" class="btn btn-bottom btn-current">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                        </button>
                        <button type="button" id="right" class="btn btn-bottom btn-current">
                            <i class="fa fa-repeat" aria-hidden="true"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>