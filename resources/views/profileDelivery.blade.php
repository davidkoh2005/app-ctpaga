<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/show.css').'?v='.time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css').'?v='.time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css').'?v='.time() }}">
</head>
<body>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12 mx-auto">
        <div class="card text-center">
            <div class="card-header notBorder">
                <img src="{{ asset('images/email/LOGO.png') }}" alt="" style="padding: 10px; display: block; margin: 0 auto; width: 50%; max-width: 200px;">
            </div>
            <div class="card-body" style=" margin-right: 20px; margin-left: 20px;">
                <div id="title"><h5 class="center">Datos Delivery</h5></div>
                @if($picture)
                <div class="row justify-content-center zoom">
                    <img class="rounded-circle" src="{{$picture->url}}" width="100px" height="100px">
                </div>
                @endif
                <div class="row">&nbsp;</div>
                <label><strong>Nombre: </strong>{{$delivery->name}}</label> <br>
                <label><strong>Teléfono: </strong>{{$delivery->phone}}</label> <br>
                <label><strong>Correo: </strong>{{$delivery->email}}</label> 

                <div class="row">&nbsp;</div>

                <div id="title"><h5 class="center">Vehículo Delivery</h5></div>
                <label><strong>Marca: </strong>{{$delivery->mark?? 'Sin Descripción'}}</label> <br>
                <label><strong>Model: </strong>{{$delivery->model?? 'Sin Descripción'}}</label> <br>
                <label><strong>Número de placa: </strong>{{$delivery->licensePlate?? 'Sin Descripción'}}</label> <br>
                <label>
                    @if($delivery->colorHex && $delivery->colorName)
                        <strong class="positionText" >Color: </strong>
                        <span class="circleColor" style="background: {{$delivery->colorHex}} none repeat scroll 0 0;"></span>
                        <span class="positionText positionTextName">{{$delivery->colorName?? 'Sin Descripción'}}</span> 
                    @else 
                        <strong >Color: </strong> 
                        <span class="positionTextName">{{$delivery->colorName?? 'Sin Descripción'}}</span> 
                    @endif
                </label> <br>
                

                <div class="row">&nbsp;</div>

            </>
        </div>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.zoom').on('click', function() {
                console.log("entro");
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');  
            });	
        });	
    </script>
</body>
</html>