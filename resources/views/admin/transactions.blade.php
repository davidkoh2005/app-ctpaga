<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css').'?v='.time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css').'?v='.time() }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js').'?v='.time() }}"></script>
</head>
<body class="body-admin">
    <div class="loader"></div>
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
                                        <option value="Selecionar Tipo de Pago" disabled>Selecionar Tipo de Pago</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Pago Móvil">Pago Móvil</option>
                                        <option value="Zelle">Zelle</option>
                                        <option value="Square">Square</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Bitcoin">Bitcoin</option>
                                        <option value="Pago en Efectivo">Pago en Efectivo</option>
                                        <option value="Tienda Fisica">Tienda Fisica</option>
                                    </select>
                                </div>

                                <label class="col-sm-2 col-form-label">Moneda</label>
                                <div class="col">
                                    <select class="form-select form-control" name="selectCoin" id="selectCoin">
                                        <option value="Selecionar Moneda" disabled>Selecionar Moneda</option>
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
                                    <a type="button" class="remove-transactions btn" href="{{route('admin.transactions')}}">Limpiar</a>
                                </div>
                            </div>
                            <input type="hidden" name="statusFile" id="statusFile" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            @if($idCommerce > 0)
            <label for="" class="nameCompany"><strong>Nombre de Compañia:</strong> {{ $companyName}} </label>
            @endif

            <div class="row">&nbsp;</div>
        
            <div class="row justify-content-center">
                <div class="col-11">
                    <strong class="download">Descargar Reporte en:</strong>
                    <input type="image" id="btnPDF" src="{{ asset('images/pdf.png') }}" width="45px" height="50px">
                    <input type="image" id="btnExcel" src="{{ asset('images/excel.png') }}" width="50px" height="60px" style="margin-left:20px">
                </div>
            </div>
        
            <div class="tableShow">
                <table id="table_id" class="table table-bordered display" style="width:100%;">
                    <thead>
                        <tr class="table-title">
                        <th scope="col"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"></th>
                            <th scope="col">#</th>
                            @if($idCommerce == 0)<th scope="col">Nombre Compañia</th>@endif
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">Código</th>
                            <th scope="col">Total</th>
                            <th scope="col">Pago</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                        <td><input type="checkbox" class="check-Payment" data-id="{{ $transaction->id }}" data-status="{{$transaction->statusPayment}}"></td>
                            <th scope="row">{{ $transaction->id }}</th>
                            @if($idCommerce == 0)<td>{{ $transaction->name }}</td>@endif
                            <td>{{ $transaction->nameClient}}</td>
                            <td>{{ $transaction->codeUrl }}</td>
                            <td>@if($transaction->coin == 0) $ @else Bs @endif {{ number_format((floatval($transaction->total)), 2, ',', '.') }}</td>
                            <td> {{$transaction->nameCompanyPayments}}</td>
                            <td>
                                @if($transaction->statusPayment == 0)
                                    <div class="cancelled">Cancelado</div>
                                @elseif($transaction->statusPayment == 1)
                                    <div class="pending">Pendiente</div>
                                @else
                                    <div class="completed">Completado</div>
                                @endif
                            </td>
                            <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                            <td>
                                <button class="btn btn-bottom" onClick="showProduct({{$transaction->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Productos"><i class="material-icons">shopping_bag</i></button>
                                @if($transaction->nameCompanyPayments == 'Transferencia' || $transaction->nameCompanyPayments == 'Pago Móvil')
                                    <button class="btn btn-bottom" onClick="showTransactions({{$transaction->id}}, '{{$transaction->nameCompanyPayments}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">receipt</i></button>
                                @elseif($transaction->nameCompanyPayments == 'Zelle')
                                    <button class="btn btn-bottom" onClick="showTransactions({{$transaction->id}}, '{{$transaction->nameCompanyPayments}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">receipt</i></button>
                                @elseif($transaction->nameCompanyPayments == 'Bitcoin')
                                    <button class="btn btn-bottom" onClick="showTransactions({{$transaction->id}}, '{{$transaction->nameCompanyPayments}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Transacciones"><i class="material-icons">receipt</i></button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row" style="margin-left:20px; margin-bottom:60px;">
                <div class="text-left" style="margin-top:12px; margin-left:25px">
                    <label style="color:black;"><input type="checkbox" class="selectAll" id="selectAllCheck-Payment" name="selectAllCheck-Payment"> Seleccionar Todos </label>
                </div>
                <div class="text-left" style="margin-left:40px;">
                    <strong class="addMarginRight">Acciones:</strong>
                    <label class="content-select">
                        <select class="addMargin" name="changeStatus" id="changeStatus">
                            <option value="-1" disabled selected>Cambiar Estado</option>
                            <option value="0">Cancelado</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Completado</option>
                        </select>
                    </label>
                    
                </div>
            </div>

        </div>
    </div>

    <!--- Modal products -->
    <div class="modal fade" id="showProductsOrTransactionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div id="showTransactions"></div>
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
                $('#showProductsOrTransactionsModal').modal('show'); 
                $('#showTransactions').html(data.html);
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#showProductsOrTransactionsModal').modal('hide'); 
                $('#showTransactions').html();
            });
        }

        function showTransactions(id, payment)
        {
            $.ajax({
                url: "{{route('admin.transactionsPayment')}}", 
                data: {"id" : id, "payment": payment},
                type: "GET",
            }).done(function(data){
                $('#showProductsOrTransactionsModal').modal('show'); 
                $('#showTransactions').html(data.html);
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#showProductsOrTransactionsModal').modal('hide'); 
                $('#showTransactions').html();
            });
        }

        $(".main-panel").perfectScrollbar('update');
        $( ".loader" ).fadeOut("slow"); 

        $('#changeStatus').change(function(){
            selectID = [];
            var error = false;
            var status = $(this).val();
            if (status >= 0){
                $("input:checked.check-Payment").each(function () {
                    var id = $(this).data("id");
                    var statuscheck = $(this).data("status");

                    if(statuscheck == status || statuscheck ==2){
                        error = true;
                    }
                    selectID.push(id);
                });

                $("#changeStatus option[value='-1']").prop('selected', true);

                if(selectID.length >0 && !error){
                    $( ".loader" ).fadeIn("slow"); 
                    $.ajax({
                        url: "{{route('admin.changeStatusPayment')}}", 
                        data: {"selectId" : selectID, "status" : status },
                        type: "POST",
                    }).done(function(data){
                        $( ".loader" ).fadeOut("slow"); 
                        if(data.status == 201)
                            alertify.success('Guardado correctamente');
                        
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }).fail(function(result){
                        $( ".loader" ).fadeOut("slow"); 
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }); 
                }else if (selectID.length == 0 && !error){
                    alertify.error('Debe seleccionar al menos un transaccion');
                }
                else{
                    alertify.error('Debe seleccionar transacciones con estado correctamente');
                }
            }
            
        });
    </script>
</body>
</html>