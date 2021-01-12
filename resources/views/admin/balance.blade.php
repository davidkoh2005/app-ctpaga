<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="../../css/balance.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="../../js/datatables.min.js"></script>
    <script src="../../js/dashboard/script.js" type="text/javascript"></script>
</head>
<body class="body-admin">
  @include('auth.menu')
    <div class="main-panel">
      @include('admin.navbar')
        
        <div class="tableShow" id="topBalance">
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
                        <td>@if($balance->coin == 0 )  USD @else Bs @endif</td>
                        <td>@if($balance->coin == 0 )  $ @else Bs @endif {{ $balance->total }}</td>
                        <td>

                            <form method='POST' action="{{route('admin.transactionsSearch')}}">
                                <div class="row">
                                    <div class="col">
                                        <a class="btn btn-bottom" href="{{route('admin.show', ['id' => $balance->id])}}"><i class="fa fa-eye"></i> Ver documentos</a>
                                    </div>
                                    <div class="col">
                                        <input type="hidden" name="idCommerce" value="{{$balance->commerce_id}}">
                                        <button type="submit" class="btn btn-bottom"><i class="fa fa-eye"></i> Ver transacciones</button>
                                    </div>
                                </div>
                            </form>
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
            $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
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
        $(".main-panel").perfectScrollbar('update');
    </script>
    
</body>
</html>