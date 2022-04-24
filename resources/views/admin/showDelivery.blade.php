<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
</head>
<body class="body-admin">
<div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')

        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="col-12">
            <div class="tableShow">
            <h3 class="center black"> <strong>Delivery Disponible</strong> </h3>
            <div class="row">&nbsp;</div>
            <label class="black"> <strong>Orden:</strong> {{$codeUrl}} </label>
            <div class="row">&nbsp;</div>
            <label class="black"> <strong>Commerciante</strong> </label> <br>
            <label class="black"> <strong>Nombre:</strong> {{$commerce->name}} </label> <br>
            <label class="black"> <strong>Dirección:</strong> {{$commerce->address}} </label> <br>
            <div class="row">&nbsp;</div>

            <table id="table_delivery" class="table table-bordered mb-5 display" width="100%">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Teléfono</th> 
                        <th scope="col">Ubicación</th>
                        <th scope="col">Pedido</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr>
                        <th scope="row">{{ $delivery->id }}</th>
                        <td>{{ $delivery->name }}</td>
                        <td>{{ $delivery->phone}}</td> 
                        <td>{{$delivery->addressPosition}}</td>
                        <td>
                            @php
                                if(json_decode($delivery->codeUrlPaid)){
                                    $paidAll = '';
                                    foreach(json_decode($delivery->codeUrlPaid) as $item)
                                    {
                                        $paidAll .= $item.", ";
                                    }
                                    $paidAll = substr($paidAll, 0, -2);

                                    echo $paidAll;
                                }
                            @endphp
                        </td>
                        <td>
                            @if($delivery->statusAvailability)
                                <div class="availableDelivery">Disponible</div> 
                            @else
                                <div class="notAvailableDelivery">No Disponible</div> 
                            @endif
                        </td>
                        <td width="100px">
                            <button class="btn btn-bottom" onClick="publicCode('{{$codeUrl}}', '{{$delivery->id}}' )" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Enviar Orden"><i class="material-icons">send</i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        $(".main-panel").perfectScrollbar('update');

        $('#table_delivery').DataTable({
            "scrollX": true,
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Delivery",
                "infoEmpty": "Mostrando 0 to 0 of 0 Delivery",
                "infoFiltered": "(Filtrado de _MAX_ total Delivery)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Delivery",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });


        function publicCode(codeUrl, idDelivery){
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.deliverySendCodeManual')}}", 
                data: {"codeUrl" : codeUrl, "idDelivery": idDelivery},
                type: "POST",
            }).done(function(data){
                if(data.status == 201){
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.success('Asignado correctamente');
                    setTimeout(function () {
                        window.location.replace(data.url);
                    }, 2000);
                }
                else{
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.error('Delivery no se encuentra disponible');
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
