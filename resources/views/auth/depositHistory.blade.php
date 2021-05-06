<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
</head>
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
                        <form id="form-deposits" class="contact-form" method='POST' action="{{route('commerce.depositHistory')}}">

                            <div class="mb-3 row">

                                <label class="col-sm-2 col-form-label">Moneda</label>
                                <div class="col">
                                    <select class="form-select form-control" name="selectCoin" id="selectCoin">
                                        <option value="0">USA $</option>
                                        <option value="1">VE BS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>
                                @php
                                    use Carbon\Carbon;
                                @endphp
                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker">
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
                                    <a type="button" class="remove-transactions btn" href="{{route('commerce.depositHistory')}}">Limpiar</a>
                                </div>
                            </div>
                            <input type="hidden" name="statusFile" id="statusFile" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
        
            <div class="row justify-content-center">
                <div class="col-11">
                    <strong class="download">Descargar Reporte en:</strong>
                    <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                    <input type="image" id="btnExcel" src="{{ asset('images/excel.png') }}" width="50px" height="60px" style="margin-left:20px">
                </div>
            </div>
            <div class="tableShow">
                <table id="table_Deposits" class="table table-bordered display" style="width:100%;">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">Fecha</th>
                            <th scope="col">Total</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Número de Referencia</th>
                        </tr>
                    </thead>
                    <tbody>                       
                        @foreach($historyDeposits as $history)
                        <tr>
                            <td>{{$history->date }}</td>
                            @if($history->status==0)
                                <td class="received">{{$history->total}}</td>
                                <td class="received">RECIBIDO</td>
                                <td class="depositNumRef">Transacciones</td>
                            @else 
                                <td class="deposit">{{$history->total}}</td>
                                <td class="deposit">DEPOSITO</td>
                                <td class="depositNumRef">{{$history->numRef}}</td>
                            @endif
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
        var selectCoin = '{{$selectCoin}}';
        $("#selectCoin option[value='"+ selectCoin +"']").attr("selected",true);
    
        $('#btnPDF').on('click', function() {
            $('#statusFile').val("PDF");
            $('#form-deposits').submit();
        });

        $('#btnExcel').on('click', function() {
            $('#statusFile').val("EXCEL");
            $('#form-deposits').submit();
        });
    </script>
</body>
</html>