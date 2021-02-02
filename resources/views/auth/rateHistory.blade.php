<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
</head>
@php
    use Carbon\Carbon;
@endphp
<body class="body-admin">
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
                        <form id="rate-form" class="contact-form" method='POST' action="{{route('commerce.rate')}}">

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
                                    <a type="button" class="remove-transactions btn" href="{{route('commerce.rate')}}">Limpiar</a>
                                </div>
                            </div>
                            <input type="hidden" name="statusFile" id="statusFile" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="col-11">
                <strong class="download">Descargar Reporte en:</strong>
                <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                <input type="image" id="btnExcel" src="{{ asset('images/excel.png') }}" width="50px" height="60px" style="margin-left:20px">
            </div>
            <div class="tableShow">
                <table id="table_Rate" class="table table-bordered mb-5 display">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">Fecha</th>
                            <th scope="col">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rates as $rate)
                        <tr>
                            <td>{{Carbon::parse($rate->date)->format('Y-m-d g:i A') }}</td>
                            <td>Bs {{number_format($rate->rate, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    @include('admin.bookshopBottom')
    <script> 
        var statusMenu = "{{$statusMenu}}";
        $('#btnPDF').on('click', function() {
            $('#statusFile').val("PDF");
            $('#rate-form').submit();
        });

        $('#btnExcel').on('click', function() {
            $('#statusFile').val("EXCEL");
            $('#rate-form').submit();
        });
    </script>
</body>
</html>