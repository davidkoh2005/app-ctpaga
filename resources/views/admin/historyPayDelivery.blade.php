<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css').'?v='.time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css').'?v='.time() }}">
    @include('admin.bookshop')
    <script src="{{ asset('js/dashboard/script.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
</head>
<body class="body-admin">
    <div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
      @include('auth.navbar')
        <div class="justify-content-center" id="row">
            <div class="col-10">
                <div class="card card-Transactions">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success" style="margin:15px;">
                        <form method='POST' action="{{route('admin.historyPayDelivery')}}">
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12 col-form-label">Nombre Delivery</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchName" id="searchName" value="{{$searchName}}">
                                </div>

                                <label class="col-sm-2 col-form-label">Buscar Estado</label>
                                <label class="content-select col">
                                    <select class="addMargin" name="searchStatus" id="searchStatus">
                                        <option value="0" disabled>Estado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Completado</option>
                                    </select>
                                </label>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>
                                @php
                                    use Carbon\Carbon;
                                @endphp
                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker-admin">
                                    <input type="text" class="form-control" name="startDate" placeholder="Fecha Inicial" value="{{Carbon::parse(str_replace('/','-',$startDate))->format('d/m/Y')}}" autocomplete="off"/>
                                        <span class="input-group-addon"> Hasta </span>
                                        <input type="text" class="form-control" name="endDate" placeholder="Fecha Final" value="{{Carbon::parse(str_replace('/','-',$endDate))->format('d/m/Y')}}" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>


                            <div class="row">&nbsp;</div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                        </form>
                                <div class="col-6">
                                    <form id="formRemove" method='POST' action="{{route('admin.historyPayDelivery')}}"> 
                                    <a type="button" class="remove-transactions btn" href="javascript:$('#formRemove').submit();">Limpiar</a>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tableShow">
            <table id="table_order" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"></th>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha Pedido</th>
                        <th scope="col">Pedido</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><input type="checkbox" class="check-Payment" data-id="{{ $order->id }}" data-status="{{$order->statusPayDelivery}}"></td>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td> {{date('d/m/Y',strtotime($order->date))}}</td>
                        <td>{{ $order->codeUrl }}</td>
                        <td>{{ $order->state }}, {{ $order->municipalities }} </td>
                        <td>
                            @if($order->statusPayDelivery == 1)
                                <div class="pending">Pendiente</div>
                            @else
                                <div class="completed">Completado</div>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-bottom" onClick="showProduct({{$order->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Pedido"><i class="material-icons">shopping_bag</i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="text-left" style="margin-top:12px; margin-left:25px">
                    <label style="color:black;"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"> Seleccionar Todos </label>
                </div>
                <div class="text-left" style="margin-left:40px;">
                    <strong class="addMarginRight">Acciones:</strong>
                    <label class="content-select">
                        <select class="addMargin" name="changeStatus" id="changeStatus">
                            <option value="0" disabled selected>Cambiar Estado</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Completado</option>
                        </select>
                    </label>
                    
                </div>
            </div>
        </div>
    </div>

    <!--- Modal products -->
    <div class="modal fade" id="showProductsOrTransactionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div id="showTransactions"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="showPayment"></div>
    @include('admin.bookshopBottom')
    <script> 
        var rowSelect;
        var statusMenu = "{{$statusMenu}}";
        var statusSelect = false;
        var selectID = [];

        var searchStatus ='{{$searchStatus}}';
        $("#searchStatus option[value='"+ searchStatus +"']").prop("selected",true);

        $( ".loader" ).fadeOut("slow"); 
        $('#changeStatus').change(function(){
            selectID = [];
            var error = false;
            var status = $(this).val();
            if (status != 0){
                $("input:checked.check-Payment").each(function () {
                    var id = $(this).data("id");
                    var statuscheck = $(this).data("status");
                    
                    if(statuscheck > status || statuscheck == status || (statuscheck+1 != status)){
                        error = true;
                    }
                    selectID.push(id);
                });

                $("#changeStatus option[value='0']").prop("selected",true);

                if(selectID.length >0 && !error)
                    if(status == 3){
                        statusSelect = true;
                        showDataPayment(0, false)
                    }
                    else{
                        $( ".loader" ).fadeIn("slow"); 
                        $.ajax({
                            url: "{{route('admin.changeStatusPayDelivery')}}", 
                            data: {"selectId" : selectID, "status" : status },
                            type: "POST",
                        }).done(function(data){
                            $( ".loader" ).fadeOut("slow"); 
                            if(data.status == 201)
                                alertify.success('Guardado correctamente!');

                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }).fail(function(result){
                            $( ".loader" ).fadeOut("slow"); 
                            alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                        }); 
                    }
                else if (selectID.length == 0 && !error){
                    alertify.error('Debe seleccionar al menos un pedido');
                }
                else{
                    alertify.error('Debe seleccionar pedidos con estado correctamente');
                }
            }
            
        });

        $(document).ready( function () {
            $('#table_order').DataTable({
                "scrollX": true,
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Pedidos",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Pedidos",
                    "infoFiltered": "(Filtrado de _MAX_ total Pedidos)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Pedidos",
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

            $(".selectAll").on( "click", function(e) {
                $('input:checkbox').prop('checked', this.checked);  
            });

            $('input:checkbox').on( "click", function(e) {
                if($(".check-Payment").length == $(".check-Payment:checked").length) { 
                    $(".selectAll").prop("checked", true);
                }else {
                    $(".selectAll").prop("checked", false);            
                }
            });
        });
        $(".main-panel").perfectScrollbar('update');

        function showProduct(id)
        {
            $.ajax({
                url: "{{route('admin.transactionsShow')}}", 
                data: {"id" : id},
                type: "GET",
            }).done(function(data){
                $('#showProductsOrTransactionsModal').modal('show'); 
                $('#showTransactions').html(data.html);
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#showProductsOrTransactionsModal').modal('hide'); 
                $('#showTransactions').html();
            });
        }
    </script>
    
</body>
</html>