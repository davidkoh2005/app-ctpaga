<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="../../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
 
<script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body class="body-admin">
    @include('admin.navbar')
    
    <div class="tableShow">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">#</th>
                    <th scope="col">Nombre Compañia</th>
                    <th scope="col">RIF</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Acciones</th>
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
                    <td>
                        <a class="btn btn-bottom" href="{{route('form.store', ['userUrl' => $commerce->userUrl])}}" target="_blank"><i class="fa fa-eye"></i> Ver Tienda</a>
                        <a class="btn btn-bottom" href="{{route('admin.commercesShow', ['id' => $commerce->id])}}"><i class="fa fa-eye"></i> Ver Transacciones</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script> 
        $(document).ready( function () {
            $('#table_id').DataTable({
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
    </script>
</body>
</html>