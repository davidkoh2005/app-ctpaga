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
      @include('auth.navbar')
      <div class="justify-content-center" id="row">
            <div class="col-10">
                <div class="card card-Balance">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success">
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.balance')}}">  
                            <div class="mb-3 row">             
                                <label class="col-sm-2 col-form-label">Moneda</label>
                                <div class="col">
                                    <select class="form-select form-control" name="selectCoin" id="selectCoin">
                                        <option value="Selecionar Moneda">Selecionar Moneda</option>
                                        <option value="0">USA $</option>
                                        <option value="1">VE BS</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">&nbsp;</div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                                <div class="col-6">
                                    <a type="button" class="remove-balance btn" href="{{route('admin.balance')}}">Limpiar</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tableShow" id="topBalance">
            <table id="table_id" class="table table-bordered mb-5 display">
                <thead>
                    <tr class="table-title">
                        <th scope="col"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"></th>
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
                        <td><input type="checkbox" class="check-Payment" data-id="{{ $balance->id }}"></td>
                        <td>{{ $balance->id }}</td>
                        <td>{{ $balance->name }}</td>
                        <td>@if($balance->coin == 0 )  USD @else Bs @endif</td>
                        <td>@if($balance->coin == 0 )  $ @else Bs @endif {{ $balance->total }}</td>
                        <td>
                            <botton class="pay btn btn-bottom" onclick="showDataPayment({{$balance->id}}, true)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Pagar Comerciante"><i class="material-icons">payment</i></botton>
                            <a class="btn btn-bottom" href="{{route('admin.transactionsSearchId', ['id' => $balance->commerce_id])}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">description</i></a>
                            <a class="btn btn-bottom" href="" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Generar reporte de pago"><i class="material-icons">get_app</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-3" style="top:12px;">
                    <input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"> Seleccionar Todos 
                </div>
                <div class="col-4">
                <strong>Acciones:</strong> &nbsp;&nbsp;&nbsp; <botton class="pay btn btn-bottom" onclick="showDataPayment(0, false)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Pagar todos Comerciantes con Banco de Venezuela"><i class="material-icons">payment</i></botton>
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
        $("#selectCoin option[value='"+ selectCoin +"']").attr("selected",true);

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
                    data: {"selectId" : selectID, "status" : status },
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