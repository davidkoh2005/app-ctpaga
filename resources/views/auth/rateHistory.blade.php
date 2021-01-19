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
    <script type="text/javascript" src="../../js/transactions.js"></script>
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
                    <div class="card-body has-success">
                        <form class="contact-form" method='POST' action="{{route('commerce.rate')}}">

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

                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
        
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
    </script>
</body>
</html>