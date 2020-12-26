<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../../css/styleForm.css">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
    
    <script src="../../js/i18n/es.js"></script>
    <script src="../../js/global.js"></script>
    <script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body>
    @include('admin.navbar')
    
    <div class="tableShow">
        <table id="table_id" class="table table-bordered mb-5 display">
            <thead>
                <tr class="table-title">
                    <th scope="col">#</th>
                    <th scope="col">Nombre Compañia</th>
                    <th scope="col">Nombre Cliente</th>
                    <th scope="col">Total</th>
                    <th scope="col">Pago</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $transaction->id }}</th>
                    <td>{{ $transaction->name }}</td>
                    <td>{{ $transaction->nameClient}}</td>
                    <td>@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                    <td> {{$transaction->nameCompanyPayments}}</td>
                    <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                    <td>
                        <button class="btn btn-bottom" onClick="showProduct({{$transaction->id}})">
                            <i class="fa fa-eye"></i> Ver
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!--- Modal Picture -->
    <div class="modal fade" id="productsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <label><strong>Productos y/o Servicios:</strong></label>
                    <div id="showProducts"></div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitReason" value="Enviar Razón">
                        <div class="row marginAuto"id="loadingReason">
                            <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script> 
        $(document).ready( function () {
            $('#productsModal').modal('hide'); 

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

        function showProduct(id)
        {
            $.ajax({
                url: "{{route('admin.transactionsShow')}}", 
                data: {"id" : id},
                type: "GET",
            }).done(function(data){
                $('#showProducts').html(data.html);
                $('#productsModal').modal('show'); 
            }).fail(function(result){});
        }
    </script>
</body>
</html>