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
<body class="body-admin">
    @include('auth.menu')
    <div class="main-panel">
        @include('admin.navbar')
        <div class="justify-content-center" id="row">
            <div class="col-10">
                <div class="card card-Transactions">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success">
                        @if($idCommerce >0)
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.transactionsSearchId', ['id' => $idCommerce])}}">
                        @else
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.transactionsSearch')}}">
                        @endif
                            <div class="mb-3 row">
                                @if($idCommerce == 0)
                                <label class="col-md-2 col-12  col-form-label">Nombre Compañia</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameCompany" id="searchNameCompany" value="{{$searchNameCompany}}">
                                </div>
                                @endif

                                <label class="col-md-2 col-12 col-form-label">Nombre Cliente</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchNameClient" id="searchNameClient" value="{{$searchNameClient}}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Tipo de Pago</label>
                                <div class="col">
                                    <select class="form-select form-control" name="selectPayment" id="selectPayment">
                                        <option value="Selecionar Tipo de Pago">Selecionar Tipo de Pago</option>
                                        <option value="E-sitef">E-sitef</option>
                                        <option value="Stripe">Stripe</option>
                                        <option value="Pago en Efectivo">Pago en Efectivo</option>
                                        <option value="Tienda Fisica">Tienda Fisica</option>
                                    </select>
                                </div>

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
                                    <a type="button" class="remove-transactions btn" href="{{route('admin.transactions')}}">Limpiar</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            @if($idCommerce > 0)
            <label for="" class="nameCompany"><strong>Nombre de Compañia:</strong> {{ $companyName}} </label>
            @endif
        
            <div class="tableShow">
                <table id="table_id" class="table table-bordered mb-5 display">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">#</th>
                            @if($idCommerce == 0)<th scope="col">Nombre Compañia</th>@endif
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">Total</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transaction->id }}</th>
                            @if($idCommerce == 0)<td>{{ $transaction->name }}</td>@endif
                            <td>{{ $transaction->nameClient}}</td>
                            <td>@if($transaction->coin == 0) $ @else Bs @endif {{ $transaction->total}}</td>
                            <td> {{$transaction->nameCompanyPayments}}</td>
                            <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                            <td>
                                <button class="btn btnTransaction btn-bottom" onClick="showProduct({{$transaction->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Productos"><i class="material-icons">shopping_bag</i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--- Modal products -->
    <div class="modal fade" id="productsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div id="showProducts"></div>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.bookshopBottom')
    <script> 
        var statusMenu = "{{$statusMenu}}";
        var selectCoin = '{{$selectCoin}}';
        var selectPayment ='{{$selectPayment}}';
        $("#selectCoin option[value='"+ selectCoin +"']").attr("selected",true);
        $("#selectPayment option[value='"+ selectPayment +"']").attr("selected",true);

        function showProduct(id)
        {
            $.ajax({
                url: "{{route('admin.transactionsShow')}}", 
                data: {"id" : id},
                type: "GET",
            }).done(function(data){
                $('#productsModal').modal('show'); 
                $('#showProducts').html(data.html);
            }).fail(function(result){
                $('#productsModal').modal('hide'); 
                $('#showProducts').html();
            });
        }
    </script>
</body>
</html>