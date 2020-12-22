<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
 
<script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">Ctpaga</a>
        <form class="d-flex" action="{{route('admin.logout')}}">
            <button class="btn btn-light" type="submit">Salir</button>
        </form>
    </div>
    </nav>
    

    <div class="tableShow">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">#</th>
                    <th scope="col">Nombre Compañia</th>
                    <th scope="col">Moneda</th>
                    <th scope="col">Total</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($balances as $balance)
                <tr>
                    <th scope="row">{{ $balance->id }}</th>
                    <td>{{ $balance->name }}</td>
                    <td>@if($balance->coin == 0 )  $ @else Bs @endif</td>
                    <td>@if($balance->coin == 0 )  $ @else Bs @endif {{ $balance->total }}</td>
                    <td><a class="btn btn-current" href="{{route('admin.show', ['id' => $balance->id])}}"><i class="fa fa-eye"></i> Ver</a></td>
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Balances",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Balances",
                    "infoFiltered": "(Filtrado de _MAX_ total Balances)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Balances",
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