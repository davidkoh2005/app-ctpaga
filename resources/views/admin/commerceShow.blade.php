<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="../../css/show.css">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/commerceShow.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
 
<script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body class="body-admin">
    @include('admin.navbar')

    <div class="row">&nbsp;</div>

    <div class="row showData">
        <div class="col-md-6 col-12">
            <div class="card text-center">
                <div class="card-header">
                    Datos Personal
                </div>
                <div class="card-body">
                    <label><strong>Nombre: </strong>{{$user->name}}</label> <br>
                    <label><strong>Telefono: </strong>{{$user->phone}}</label> <br>
                    <label><strong>Dirección: </strong>{{$user->address}}</label> <br>
                    <label><strong>Correo: </strong>{{$user->email}}</label> 

                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="card text-center">
                <div class="card-header">
                    Datos de Empresa
                </div>
                <div class="card-body">
                    <label><strong>Nombre: </strong>{{$commerce->name}}</label> <br>
                    <label><strong>Rif: </strong>{{$commerce->rif}}</label> <br>
                    <label><strong>Telefono: </strong>{{$commerce->phone}}</label> <br>
                    <label><strong>Dirección: </strong>{{$commerce->address}}</label> <br>
                    <label><strong>Link: </strong><a href="{{route('form.store', ['userUrl' => $commerce->userUrl])}}" class="tienda">Tienda</a></label> <br>
                </div>
            </div>
        </div>
    </div>

    <div class="row">&nbsp;</div>
    
    <div class="tableShow">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">#</th>
                    <th scope="col">Nombre Cliente</th>
                    <th scope="col">Total</th>
                    <th scope="col">Pago </th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $transaction->id }}</th>
                    <td>{{ $transaction->nameClient}}</td>
                    <td>@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                    <td> {{$transaction->nameCompanyPayments}}</td>
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Transacciones",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Transacciones",
                    "infoFiltered": "(Filtrado de _MAX_ total Transacciones)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Transacciones",
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