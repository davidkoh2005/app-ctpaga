<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
</head>
<body class="body-admin">
    <div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
      @include('auth.navbar')
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="tableShow">
            <table id="table_id" class="table table-bordered mb-5 display" width="100%">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo electrónico</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr>
                        <th scope="row">{{ $delivery->id }}</th>
                        <td>{{ $delivery->name }}</td>
                        <td>{{ $delivery->phone }}</td>
                        <td>{{ $delivery->email }}</td>
                        <td>
                            <div class="row justify-content-center align-items-center">
                                <i class="material-icons iconsClose">close</i>
                                <label class="switch" id="switch">
                                    @if($delivery->status==1)
                                        <input type="checkbox" id="switchAuth" name="switchAuth" data-id="{{ $delivery->id }}" data-status="{{$delivery->status}}" checked>
                                    @else
                                        <input type="checkbox" id="switchAuth" name="switchAuth" data-id="{{ $delivery->id }}" data-status="{{$delivery->status}}" >
                                    @endif
                                    <span class="slider round"></span>
                                </label>
                                <i class="material-icons iconsVerified">verified_user</i>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";

        $(document).ready( function () {
            $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#table_id').DataTable({
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
        });
        $(".main-panel").perfectScrollbar('update');

        $( document ).on( 'click', '#switchAuth', function(){
            var thisSwitch = this;
            var status = $(this).is(':checked');
            var idDelivery = $(this).data("id");
            $.ajax({
                url: "{{route('admin.changeStatusDelivery')}}", 
                data: {"id" : idDelivery,  "status": status},
                type: "POST",
            }).done(function(result){
                if(result.status == 201){
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.success('Estado de comerciante guardado correctamente!');
                }else{
                    $(thisSwitch).prop( "checked", !status);
                    alertify.error('Error intentalo de nuevo mas tardes!');
                }
            }).fail(function(result){
                $(thisSwitch).prop( "checked", !status);
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        });
    </script>
</body>
</html>