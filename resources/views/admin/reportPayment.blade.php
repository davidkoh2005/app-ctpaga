<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="../../css/styleForm.css">
    <link rel="stylesheet" type="text/css" href="../../css/balance.css">
    @include('admin.bookshop')
</head>
<body class="body-admin">
  @include('auth.menu')
    <div class="main-panel">
      @include('auth.navbar')
      <div class="justify-content-center" id="row">
            <div class="col-10">
                <div class="card card-Report">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success" style="margin:15px;">
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.reportPayment')}}">  
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12  col-form-label">Nombre Compañia</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameCompany" id="searchNameCompany" value="{{$searchNameCompany}}">
                                </div>

                                <label class="col-md-2 col-12  col-form-label">Numero Referencia</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="numRef" id="numRef" value="{{$numRef}}">
                                </div>
                            </div>

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

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>

                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" class="form-control" name="startDate" placeholder="Fechan Inicial" value="{{$startDate}}" autocomplete="off"/>
                                        <span class="input-group-addon"> Hasta </span>
                                        <input type="text" class="form-control" name="endDate" placeholder="Fecha Final" value="{{$endDate}}" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">&nbsp;</div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                                <div class="col-6">
                                    <a type="button" class="remove-report btn" href="{{route('admin.reportPayment')}}">Limpiar</a>
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
                        <th scope="col">#</th>
                        <th scope="col">Nombre Compañia</th>
                        <th scope="col">Numero Referencia</th>
                        <th scope="col">Moneda</th>
                        <th scope="col">Total</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <th scope="row">{{ $deposit->id }}</th>
                        <td>{{ $deposit->name }}</td>
                        <td>{{ $deposit->numRef }}</td>
                        <td>@if($deposit->coin == 0 )  USD @else Bs @endif</td>
                        <td>@if($deposit->coin == 0 )  $ @else Bs @endif {{ $deposit->total }}</td>
                        <td>{{$deposit->date}}</td>
                        <td><div class="completed">Completado</div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="showPayment"></div>
    @include('admin.bookshopBottom')
    <script> 
        var statusMenu = "{{$statusMenu}}";
        var selectCoin = '{{$selectCoin}}';
        $("#selectCoin option[value='"+ selectCoin +"']").attr("selected",true);


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

            var date = new Date();
            date.setMonth(date.getMonth()-3);
            date.setDate(1);

            $('.input-daterange').datepicker({
                startDate: date,
                endDate: new Date(),
                language: "es",
                todayHighlight: true,
                orientation: "bottom auto",
            });
        });
        $(".main-panel").perfectScrollbar('update');
    </script>
    
</body>
</html>