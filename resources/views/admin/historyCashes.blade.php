<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/script.js') }}" type="text/javascript"></script>
</head>
<body class="body-admin">
    @php
        use Carbon\Carbon;
    @endphp
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
                        <form id="form-history" class="contact-form" method='POST' action="{{route('admin.historyCashes')}}">
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12  col-form-label">Nombre </label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameDelivery" id="searchNameDelivery" value="{{$searchNameDelivery}}">
                                </div>

                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>
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
                                <div class="col-6">
                                    <a type="button" class="remove-transactions btn" href="{{route('admin.historyCashes')}}">Limpiar</a>
                                </div>
                            </div>
                            <input type="hidden" name="statusFile" id="statusFile" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">&nbsp;</div>
        <div class="col-12">        
            <div class="row justify-content-center">
                <div class="col-11">
                    <strong class="download">Descargar Reporte en:</strong>
                    <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                    <input type="image" id="btnExcel" src="{{ asset('images/excel.png') }}" width="50px" height="60px" style="margin-left:20px">
                </div>
            </div>
        </div>

        <div class="tableShow" id="topBalance">
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre Delivery</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->name }}</td>
                        <td>{{ Carbon::parse($history->date)->format('Y-m-d h:i A') }}</td>
                        <td>${{ number_format($history->total, 2, ',', '.') }}</td>
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

        $(document).ready( function () {
            //$('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
            $('#table_id').DataTable({
                "scrollX": true,
                "order": [[ 2, "desc" ]],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Historial",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Historial",
                    "infoFiltered": "(Filtrado de _MAX_ total Historial)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Historial",
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

        $('#btnPDF').on('click', function() {
            $('#statusFile').val("PDF");
            $('#form-history').submit();
        });

        $('#btnExcel').on('click', function() {
            $('#statusFile').val("EXCEL");
            $('#form-history').submit();
        });
        $(".main-panel").perfectScrollbar('update');
    </script>
    
</body>
</html>