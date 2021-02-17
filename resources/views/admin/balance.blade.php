<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/script.js') }}" type="text/javascript"></script>
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
                        <form method='POST' action="{{route('admin.balance')}}">
                            <input type="hidden" name="selectCoin" value="{{$selectCoin}}">
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Buscar Estado</label>
                                <label class="content-select">
                                    <select class="addMargin" name="searchStatus" id="searchStatus">
                                        <option value="0">Estado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">En Proceso</option>
                                        <option value="3">Completado</option>
                                    </select>
                                </label>
                            </div>


                            <div class="row">&nbsp;</div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                        </form>
                                <div class="col-6">
                                    <form id="formRemove" method='POST' action="{{route('admin.balance')}}"> 
                                    <input type="hidden" name="selectCoin" value="{{$selectCoin}}">
                                    <a type="button" class="remove-transactions btn" href="javascript:$('#formRemove').submit();">Limpiar</a>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tableShow" id="topBalance">
            <div class="row">
                @if($selectCoin == 0)
                    <label>
                        <strong>Moneda:</strong>
                        <img src="{{ asset('images/eeuu.png') }}" width="20px" height="20px">
                        USA $ 
                    </label>
                @else
                    <label>
                        <strong>Moneda:</strong>
                        <img src="{{ asset('images/venezuela.png') }}" width="20px" height="20px">
                        VE Bs 
                    </label>
                @endif
            </div>

            <div class="row">&nbsp;</div>

            <table id="table_id" class="table table-bordered mb-5 display" width="100%">
                <thead>
                    <tr class="table-title">
                        <th scope="col"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"></th>
                        <th scope="col">#</th>
                        <th scope="col">Nombre Compañia</th>
                        <th scope="col">Total</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td><input type="checkbox" class="check-Payment" data-id="{{ $deposit->id }}" data-status="{{$deposit->status}}"></td>
                        <td>{{ $deposit->id }}</td>
                        <td>{{ $deposit->name }}</td>
                        <td>@if($deposit->coin == 0 )  $ @else Bs @endif {{ $deposit->total }}</td>
                        <td>
                            @if($deposit->status == 1)
                                <div class="pending">Pendiente</div>
                            @elseif($deposit->status == 2)
                                <div class="inProcess">En Proceso</div>
                            @else
                                <div class="completed">Completado</div>
                            @endif
                        </td>
                        <td>
                            <botton class="pay btn btn-bottom" onclick="showDataPayment({{$deposit->id}}, true)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Pagar Comerciante"><i class="material-icons">payment</i></botton>
                            <a class="btn btn-bottom" href="{{route('admin.transactionsSearchId', ['id' => $deposit->commerce_id])}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">description</i></a>
                            @if($selectCoin == 1)
                            <a class="btn btn-bottom" onclick="downloadTxt({{$deposit->id}}, true)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Generar reporte de pago"><i class="material-icons">get_app</i></a>
                            @endif
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
                    <botton class="pay btn btn-bottom" onclick="showDataPayment(0, false)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Pagar comerciantes seleccionados"><i class="material-icons">payment</i></botton>
                    @if($selectCoin == 1)
                    <a class="btn btn-bottom" onclick="downloadTxt(0, false)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Generar reporte de pago seleccionados"><i class="material-icons">get_app</i></a>
                    @endif
                    <label class="content-select">
                        <select class="addMargin" name="changeStatus" id="changeStatus">
                            <option value="0">Cambiar Estado</option>
                            <option value="1">Pendiente</option>
                            <option value="2">En Proceso</option>
                            <option value="3">Completado</option>
                        </select>
                    </label>
                    
                </div>
            </div>
        </div>
    </div>
    <div id="showPayment"></div>
    @include('admin.bookshopBottom')
    <script> 
        var rowSelect;
        var statusMenu = "{{$statusMenu}}";
        var selectCoin = '{{$selectCoin}}';
        var statusSelect = false;
        var selectID = [];

        var searchStatus ='{{$searchStatus}}';
        $("#searchStatus option[value='"+ searchStatus +"']").attr("selected",true);

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

                if(selectID.length >0 && !error)
                    if(status == 3){
                        $("#changeStatus option[value='0']").attr("selected",true);
                        statusSelect = true;
                        showDataPayment(0, false)
                    }
                    else{
                         $("#changeStatus option[value='0']").attr("selected",true);
                        $( ".loader" ).fadeIn("slow"); 
                        $.ajax({
                            url: "{{route('admin.changeStatus')}}", 
                            data: {"selectId" : selectID, "status" : status },
                            type: "POST",
                        }).done(function(data){
                            $( ".loader" ).fadeOut("slow"); 
                            $("#changeStatus option[value='0']").attr("selected",true);
                            if(data.status == 201)
                                alertify.success('Estado ha sido cambiado correctamente');
                        
                            location.reload()
                        }).fail(function(result){
                            $( ".loader" ).fadeOut("slow"); 
                            $("#changeStatus option[value='0']").attr("selected",true);
                            alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                        }); 
                    }
                else if (selectID.length == 0 && !error){
                    alertify.error('Debe seleccionar al menos un deposito');
                    $("#changeStatus option[value='0']").attr("selected",true);
                }
                else{
                    alertify.error('Debe seleccionar depositos con estado correctamente');
                    $("#changeStatus option[value='0']").attr("selected",true);
                }
            }
            
        });

        function downloadTxt(id, status)
        {
            var selectID = [];
            if(!status){
                $("input:checked.check-Payment").each(function () {
                    var id = $(this).data("id");
                    selectID.push(id);
                });
            }else{
                selectID.push(id);
            }

            if(selectID.length >0)
                $.ajax({
                    url: "{{route('admin.downloadTxt')}}", 
                    data: {"selectId" : selectID, "status" : status},
                    type: "POST",
                }).done(function(data){
                    if(data.status != 201)
                    {
                        alertify.error('Selección incorrecto, valido solo para cuenta de Venezuela');
                    }else{
                        var link = document.createElement("a");
                        link.download = "ctpaga";
                        link.href = data.url;
                        link.click();
                    }
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                }); 
            else 
                alertify.error('Debe seleccionar al menos un deposito');
        }

        function showDataPayment(id, status)
        {
            var selectID = [];
            if(!status){
                $("input:checked.check-Payment").each(function () {
                    var id = $(this).data("id");
                    selectID.push(id);
                });
            }else{
                selectID.push(id);
            }

            if(selectID.length >0)
                $.ajax({
                    url: "{{route('admin.showPayment')}}", 
                    data: {"selectId" : selectID, "status" : status, "selectCoin" : selectCoin },
                    type: "POST",
                }).done(function(data){
                    if(data.status == 1)
                    {
                        alertify.error('Selección incorrecto, valido solo para cuenta de Venezuela');
                    }else{
                        $('#showPayment').html(data.html);
                        $('#payModal').modal('show'); 
                    }
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    $('#payModal').modal('hide'); 
                    $('#showPayment').html();
                }); 
            else
                alertify.error('Debe seleccionar depositos');
        }

        $(document).ready( function () {
            $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#table_id').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Depositos",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Depositos",
                    "infoFiltered": "(Filtrado de _MAX_ total Depositos)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Depositos",
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
    </script>
    
</body>
</html>