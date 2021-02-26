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
    <script type="text/javascript" src="{{ asset('js/bookshop/jquery.maskMoney.min.js') }}"></script>
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
                        <form id="rate-form" class="contact-form" method='POST' action="{{route('admin.showRatePost')}}">

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>
                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control" name="startDate" placeholder="Fechan Inicial" value="{{Carbon::parse(str_replace('/','-',$startDate))->format('d/m/Y')}}" autocomplete="off"/>
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
            <div class="row" style="margin-left: 30px; margin-right: 50px;">
                <div class="col-8">
                    <strong class="download">Descargar Reporte en:</strong>
                    <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                    <input type="image" id="btnExcel" src="{{ asset('images/excel.png') }}" width="50px" height="60px" style="margin-left:20px">
                </div>
                <div class="col" style="margin-top:20px">
                    <button type="button" class="submit btn btn-bottom" id="addRate">Agregar Tasa</button>
                </div>
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

    <!--- Modal Rate -->
    <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body has-success">
                    <div class="form-group">
                        <label><strong>Tasa:</strong></label>
                        <input type="text" name="newRate" id="newRate" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitRate" value="Crear Tasa">
                        <div class="row marginAuto" id="loadingRate">
                            <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loadingTransparent.gif') }}">
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    
    @include('admin.bookshopBottom')
    <script> 
        $("#newRate").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
        $('#loadingRate').hide();
        var statusMenu = "{{$statusMenu}}";
        $('#btnPDF').on('click', function() {
            $('#statusFile').val("PDF");
            $('#rate-form').submit();
        });

        $('#btnExcel').on('click', function() {
            $('#statusFile').val("EXCEL");
            $('#rate-form').submit();
        });

        $('#addRate').on('click', function() {
            $('#rateModal').modal('show'); 
        });	

        $('#submitRate').on('click', function() {
            var rate = $('#newRate').val();
            if(!rate){
                alertify.error('Debe ingresar una tasa');
            }else{
                $('#submitRate').hide();
                $('#loadingRate').show();
                $.ajax({
                    url: "{{route('admin.newRate')}}", 
                    data: {"rate": rate},
                    type: "POST",
                }).done(function(result){
                    if(result.status == 201){
                        alertify.success('Tasa guardado correctamente!');
                        location.reload();
                    }else{
                        $('#submitRate').show();
                        $('#loadingRate').hide();
                        alertify.error('Tasa incorrecto!');
                    }
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                });
            } 
        });		
    </script>
</body>
</html>