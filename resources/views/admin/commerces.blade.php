<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
</head>
<body class="body-admin">
@include('auth.menu')
    <div class="main-panel">
      @include('auth.navbar')
        <div class="tableShow">
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre Compañia</th>
                        <th scope="col">RIF</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Estado</th>
                        <th scope="col" style="width:20%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commerces as $commerce)
                    <tr>
                        <th scope="row">{{ $commerce->id }}</th>
                        <td>{{ $commerce->name }}</td>
                        <td>{{ $commerce->rif }}</td>
                        <td>{{ $commerce->address }}</td>
                        <td>{{ $commerce->phone }}</td>
                        <td>@if($commerce->confirmed == 1)
                                <div class="confirmed">Verificado</div>
                            @else
                                <div class="unconfirmed">Pendiente</div>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-bottom" href="{{route('admin.commercesShow', ['id' => $commerce->id])}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Documentos"><i class="material-icons">verified_user</i></a>
                            <a class="btn btn-bottom" href="{{route('admin.transactionsSearchId', ['id' => $commerce->id])}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">description</i></a>
                            <a class="btn btn-bottom" href="{{route('form.store', ['userUrl' => $commerce->userUrl])}}" rel="tooltip" target="_blank" data-toggle="tooltip" data-placement="left" title="Ver Tienda"><i class="material-icons">store</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.bookshopBottom')
    <script> 
        var statusMenu = "{{$statusMenu}}";
        $(document).ready( function () {
            //$('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#table_id').DataTable({
                "scrollX": true,
                order: [[ 5, "asc" ]],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Comerciantes",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Comerciantes",
                    "infoFiltered": "(Filtrado de _MAX_ total Comerciantes)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Comerciantes",
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
    </script>
</body>
</html>