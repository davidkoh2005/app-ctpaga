<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bookshop/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/bookshop/datatables.min.js') }}"></script>
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
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr>
                        <td>{{ $delivery->id }}</td>
                        <td>{{ $delivery->name }}</td>
                        <td>{{ $delivery->phone }}</td>
                        <td>{{ $delivery->email }}</td>
                        <td>
                            <label class="content-select">
                                <select class="addMargin changeStatus" name="changeStatus" id="changeStatus" data-id="{{$delivery->id}}" data-value="{{$delivery->status}}">
                                    @if($delivery->status == 0)
                                        <option value="0" selected disabled>En Verificación</option>
                                    @else
                                        <option value="0" disabled>En Verificación</option>
                                    @endif
                                    
                                    @if($delivery->status == 1)
                                        <option value="1" selected>Confirmado</option>
                                    @else
                                        <option value="1" >Confirmado</option>
                                    @endif

                                    @if($delivery->status == 2)
                                        <option value="2" selected>Pausado</option>
                                    @else
                                        <option value="2" >Pausado</option>
                                    @endif

                                    @if($delivery->status == 3)
                                        <option value="3" selected>Rechazado</option>
                                    @else
                                        <option value="3" >Rechazado</option>
                                    @endif

                                </select>
                            </label>
                        </td>
                        <td>
                            <a class="btn btn-bottom" href="{{route('admin.deliveryShow', ['id' => $delivery->id])}}" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Delivery"><i class="material-icons">visibility</i></a>
                            <button class="btn btn-bottom" onClick="showBalance('{{$delivery->id}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Efectivo"><i class="material-icons">account_balance_wallet</i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--- Modal Delivery -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div id="showDelivery"></div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        $('#loading').show();
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

        $('.changeStatus').change(function(){
            var idDelivery = $(this).data("id");
            var value = $(this).data("value");
            var status = $(this).val();
            var thisSelect = this;
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.changeStatusDelivery')}}", 
                data: {"id" : idDelivery,  "status": status},
                type: "POST",
            }).done(function(result){
                $( ".loader" ).fadeOut("slow"); 
                if(result.status == 201){
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.success('Ha sido guardado correctamente!');
                }else{
                    $(thisSelect).find("option[value='0']").removeAttr('disabled');
                    $(thisSelect).find("option[value='"+ value +"']").prop("selected",true);
                    alertify.error('Error intentalo de nuevo mas tardes!');
                    $(thisSelect).find("option[value='0']").attr('disabled','disabled');
                    $( ".loader" ).fadeOut("slow"); 
                }
            }).fail(function(result){
                $(thisSelect).find("option[value='0']").removeAttr('disabled');
                $(thisSelect).find("option[value='"+ value +"']").prop("selected",true);
                $(thisSelect).find("option[value='0']").attr('disabled','disabled');
                $( ".loader" ).fadeOut("slow"); 
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });  
        });
        

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
                    alertify.success('Guardado correctamente!');
                }else{
                    $(thisSwitch).prop( "checked", !status);
                    alertify.error('Error intentalo de nuevo mas tardes!');
                }
            }).fail(function(result){
                $(thisSwitch).prop( "checked", !status);
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        });

        $(document).on( 'click', '#updatePayment', function(){
            var idDelivery = $('#deliveryID').val();
            $('#updatePayment').hide();
            $('#loading').removeClass("hide");
            $('#loading').addClass("show");
            $.ajax({
                url: "{{route('admin.updatePaymentDelivery')}}", 
                data: {"id" : idDelivery},
                type: "POST",
            }).done(function(result){
                $('#updatePayment').show();
                $('#loading').removeClass("show");
                $('#loading').addClass("hide");
                if(result.status == 201){
                    $('#showDelivery').html(result.html);
                    alertify.success('Ha sido guardado correctamente!');
                }else{
                    alertify.error('Error intentalo de nuevo mas tardes!');
                }
            }).fail(function(result){
                $('#updatePayment').show();
                $('#loading').removeClass("show");
                $('#loading').addClass("hide");
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        });

        function showBalance(id){
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.showBalanceDelivery')}}", 
                data: {"id" : id},
                type: "GET",
            }).done(function(data){
                $( ".loader" ).fadeOut("slow"); 
                $('#deliveryModal').modal('show'); 
                $('#showDelivery').html(data.html);
            }).fail(function(result){
                $( ".loader" ).fadeOut("slow"); 
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#deliveryModal').modal('hide'); 
                $('#showDelivery').html();
            });
        }
    </script>
</body>
</html>