<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bookshop/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/bookshop/datatables.min.js') }}"></script>
</head>
<body class="body-admin">
@include('auth.menu')
    <div class="loader"></div>
    <div class="main-panel">
      @include('auth.navbar')
        <div class="tableShow">
            <table id="table_id" class="table table-bordered mb-5 display" width="100%">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Comercios</th>
                        <th scope="col" style="width:20%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersAll as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @php 
                                $commercesAll = '';
                                foreach($user->commerces as $commerce)
                                {
                                    $commercesAll .= $commerce->name.", ";
                                }
                                $commercesAll = substr($commercesAll, 0, -2);

                                echo $commercesAll;
                            @endphp

                        </td>

                        <td>
                            <label class="content-select">
                                <select class="addMargin changeStatus" name="changeStatus" id="changeStatus" data-id="{{$user->id}}" data-value="{{$user->status}}">
                                    @if($user->status == 0)
                                        <option value="0" selected>Aceptado</option>
                                    @else
                                        <option value="0" >Aceptado</option>
                                    @endif

                                    @if($user->status == 1)
                                        <option value="1" selected>Pausado</option>
                                    @else
                                        <option value="1" >Pausado</option>
                                    @endif

                                    @if($user->status == 2)
                                        <option value="2" selected>Rechazado</option>
                                    @else
                                        <option value="2" >Rechazado</option>
                                    @endif

                                </select>
                            </label>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        $(document).ready( function () {
            $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            
            $('.changeStatus').change(function(){
                var id = $(this).data("id");
                var value = $(this).data("value");
                var status = $(this).val();
                var thisSelect = this;
                $( ".loader" ).fadeIn("slow"); 
                $.ajax({
                    url: "{{route('admin.changeStatusUser')}}", 
                    data: {"id" : id, "status" : status },
                    type: "POST",
                }).done(function(data){
                    $( ".loader" ).fadeOut("slow"); 
                    if(data.status == 201){
                        alertify.success('Estado ha sido cambiado correctamente');
                        location.reload()
                    }else{
                        $(thisSelect).find("option[value='"+value+"']").prop("selected",true);
                        $( ".loader" ).fadeOut("slow"); 
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }
                }).fail(function(result){
                    $(thisSelect).find("option[value='"+value+"']").prop("selected",true);
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                }); 
            });
            
            
            $('#table_id').DataTable({
                order: [[ 5, "asc" ]],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Usuarios",
                    "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Usuarios",
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