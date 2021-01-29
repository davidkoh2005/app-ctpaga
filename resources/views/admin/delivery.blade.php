<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="../../css/balance.css">
    @include('admin.bookshop')
    <script type="text/javascript" src="../../js/transactions.js"></script>
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
                        @if($idCommerce >0)
                        <form id="payment-form" class="contact-form" method='GET' action="{{route('admin.transactionsSearchId', ['id' => $idCommerce])}}">
                        @else
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.transactionsSearch')}}">
                        @endif
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12  col-form-label">Nombre Compañia</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameCompany" id="searchNameCompany" value="{{$searchNameCompany}}">
                                </div>

                                <label class="col-md-2 col-12 col-form-label">Nombre Cliente</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameClient" id="searchNameClient" value="{{$searchNameClient}}">
                                </div>
                            </div>

                        
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>

                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker-admin">
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
                                    <a type="button" class="remove-transactions btn" href="{{route('admin.transactions')}}">Limpiar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
        
            <div class="tableShow">
                <table id="table_id" class="table table-bordered mb-5 display">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">#</th>
                            <th scope="col">Nombre Compañia</th>
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Envio</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Alarma</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transaction->id }}</th>
                            <td>{{ $transaction->name }}</td>
                            <td>{{ $transaction->nameClient}}</td>
                            <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                            <td>{{$transaction->selectShipping}}</td>
                            <td>@if($transaction->idDelivery != null) <div class="sendDelivery">Enviado</div> @else  <div class="pendingDelivery">Pendiente</div> @endif </td>
                            <td>@if($transaction->alarm) <div class="activatedAlarm">Activado</div> @else <div class="disabledAlarm">Desactivado</div> @endif</td>
                            <td>
                                <button class="btn btn-bottom" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Enviar Código"><i class="material-icons">send</i></button>
                                <button class="btn btn-bottom" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                            </td>
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


        $(".main-panel").perfectScrollbar('update');
    </script>
</body>
</html>