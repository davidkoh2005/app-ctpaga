<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/show.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/rotate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
    @include('admin.bookshop')
</head>
<body>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')
        <section>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card text-center">
                        <div class="card-header">
                            Selfie
                        </div>
                        <div class="card-body center">
                            @if($selfie)
                                @php
                                $idSelfie = $selfie->id;
                                @endphp
                                <div class="zoom">
                                    <img src="{{url($selfie->url)}}" width="250px" height="350px">
                                </div>
                            @else
                                @php
                                $idSelfie = 0;
                                @endphp
                                <p> No tiene Foto </p>
                            @endif
                        </div>
                        @if($selfie)
                        <div class="card-footer" >
                            <input type="button" class="btn btn-bottom btn-remove" id="rejectSelfie" value="Rechazar">
                        </div>
                        @endif
                    </div>
                    <div class="row">&nbsp;</div>
                </div>
                <div class="col-md-6 col-12">
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
                            <label><strong>Link: </strong><a href="{{route('form.store', ['userUrl' => $commerce->userUrl])}}" class="tienda">Tienda</a></label> <br>

                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>
            <div class="row">
                @foreach ($pictures as $picture)
                    <div class="col-md-6 col-12">
                        <div class="card text-center">
                            <div class="card-header">
                                @if($picture->descripction == 'Identification')
                                    Identificación
                                @else
                                    {{$picture->description}}
                                @endif
                            </div>
                            <div class="card-body center">
                                <div class="zoom">
                                    <img src="{{url($picture->url)}}" width="250px" height="350px">
                                </div>
                            </div>
                            <div class="card-footer btnReject">
                                <input type="button" class="btn btn-bottom btn-remove" value="Rechazar">
                                <input type="hidden" name="idPictures" id="idPictures" value="{{$picture->id}}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>


    <!--- Modal Picture -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--- Modal Picture -->
    <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Razón:</strong></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitReason" value="Enviar Razón">
                        <div class="row marginAuto"id="loadingReason">
                            <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loading.gif') }}">
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.bookshopBottom')
    <script>
        $('#loading').hide();
        $('#loadingReason').hide();
        var idSelfie = {{$idSelfie}};
        var statusReason= 0;
        var idSelect;
        var statusMenu = "{{$statusMenu}}";
    
        $(function() {
            $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#rejectSelfie').on('click', function() {
                statusReason= 0;
                $('#reasonModal').modal('show'); 
            });	


            $('.btnReject').on('click', function() {
                statusReason= 1;
                idSelect = $(this).find('#idPictures').val();
                $('#reasonModal').modal('show'); 
            });
            
            $('#submitReason').on('click', function() {
                var reason = $('#reason').val();
                console.log(reason);
                if(!reason){
                    alertify.error('Debe ingresar alguna razón');
                }else{
                    $('#submitReason').hide();
                    $('#loadingReason').show();
                    if(statusReason == 0){
                        $.ajax({
                            url: "{{route('admin.removePicture')}}", 
                            data: {"id" : idSelfie, "reason": reason},
                            type: "POST",
                        }).done(function(result){
                            if(result.status == 201){
                                location.reload();
                            }
                        }).fail(function(result){
                            alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                        });
                    }else{
                        $.ajax({
                            url: "{{route('admin.removePicture')}}", 
                            data: {"id" : idSelect,  "reason": reason},
                            type: "POST",
                        }).done(function(result){
                            $('#reasonModal').modal('hide'); 
                            if(result.status == 201){
                                location.reload();
                            }
                        }).fail(function(result){
                            alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                        });
                    }
                }
            });		
        });	
        $(".main-panel").perfectScrollbar('update');
    </script>
</body>
</html>