<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bookshop/datatables.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('js/show.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bookshop/rotate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bookshop/datatables.min.js') }}"></script>
    @include('admin.bookshop')
</head>
<body>
    <div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')
        <section>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card text-center">
                        <div class="card-header card-headerColor">
                            Foto de Perfil
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
                        <div class="card-header card-headerColor">
                            Informacion
                        </div>
                        <div class="card-body">
                            <h5 class="center">Datos Personal</h5>
                            <label><strong>Nombre: </strong>{{$delivery->name}}</label> <br>
                            <label><strong>Teléfono: </strong>{{$delivery->phone}}</label> <br>
                            <label><strong>Dirección: </strong>{{$delivery->address}}</label> <br>
                            <label><strong>Correo: </strong>{{$delivery->email}}</label> 

                            <div class="row">&nbsp;</div>

                            <h5 class="center">Vehículo</h5>
                            <label><strong>Marca: </strong>{{$delivery->mark}}</label> <br>
                            <label><strong>Model: </strong>{{$delivery->model}}</label> <br>
                            <label><strong>Número de placa: </strong>{{$delivery->licensePlate}}</label> <br>
                            <label><strong class="positionText" >Color: </strong> <span class="circleColor" style="background: {{$delivery->colorHex}} none repeat scroll 0 0;"></span> <span class="positionText positionTextName">{{$delivery->colorName}}</span></label> <br>
                            

                            <div class="row">&nbsp;</div>
                            <label><strong>Link Delivery: </strong> <a class="linkDelivery" href="{{url('/delivery/'.$delivery->idUrl)}}" target="_blank" rel="noopener noreferrer">Link</a></label> <br>

                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">&nbsp;</div>
            <div class="row">
                @foreach ($documents as $document)
                    <div class="col-md-6 col-12">
                        <div class="card text-center">
                            <div class="card-header card-headerColor">
                                @if($document->description == 'License')
                                    Licencia
                                @elseif($document->description == 'Driving License')
                                    Carnet de circulación
                                @elseif($document->description == 'Civil Liability')
                                    Responsabilidad Civil
                                @elseif($document->description == 'Selfie')
                                    Selfie de Verificación
                                @endif
                            </div>
                            <div class="card-body center">
                                @if(strpos($document->url, '.jpg') !== false)
                                    <div class="zoom">
                                        <img src="{{url($document->url)}}" width="250px" height="350px">
                                    </div>
                                @else
                                    <a class="btnPDF" href="{{$document->url}}" target="_blank">
                                        <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                                    </a>
                                @endif
                            </div>
                            <div class="card-footer btnReject">
                                <input type="button" class="btn btn-bottom btn-remove" value="Rechazar">
                                <input type="hidden" name="idDocument" id="idDocument" value="{{$document->id}}">
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
                    <img src="" class="imagepreview img-fluid" >
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

    @include('admin.bookshopBottom')
    <script>
        $( ".loader" ).fadeOut("slow"); 
        $('#loading').hide();
        $('#loadingReason').hide();
        var idSelfie = {{ $selfie? $selfie->id : 0}};
        var statusReason= 0;
        var idSelect;
        var statusMenu = "{{$statusMenu}}";
    
        $(function() {
            //$('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#rejectSelfie').on('click', function() {
                //$('#reasonModal').modal('show');
                removePerfil(idSelfie);
            });	


            $('.btnReject').on('click', function() {
                idSelect = $(this).find('#idDocument').val();
                removeDocument(idSelect); 
            });
            
        });	
        $(".main-panel").perfectScrollbar('update');


        function removePerfil(id)
        {
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.removePerfil')}}", 
                data: {"id" : id,},
                type: "POST",
            }).done(function(result){
                $( ".loader" ).fadeOut("slow");
                //$('#reasonModal').modal('hide'); 
                if(result.status == 201){
                    alertify.success('Ha sido eliminado correctamente!');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            }).fail(function(result){
                $( ".loader" ).fadeOut("slow");
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        }

        function removeDocument(id)
        {
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.removeDocumentDelivery')}}", 
                data: {"id" : id },
                type: "POST",
            }).done(function(result){
                $( ".loader" ).fadeOut("slow");
                if(result.status == 201){
                    alertify.success('Ha sido eliminado correctamente!');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            }).fail(function(result){
                $( ".loader" ).fadeOut("slow");
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        }
    </script>
</body>
</html>