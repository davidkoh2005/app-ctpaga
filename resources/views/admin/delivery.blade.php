<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
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
                                @php
                                    use Carbon\Carbon;
                                @endphp
                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker-admin">
                                        <input type="text" class="form-control" name="startDate" placeholder="Fechan Inicial" value="{{Carbon::parse($startDate)->format('d/m/Y')}}" autocomplete="off"/>
                                        <span class="input-group-addon"> Hasta </span>
                                        <input type="text" class="form-control" name="endDate" placeholder="Fecha Final" value="{{Carbon::parse($endDate)->format('d/m/Y')}}" autocomplete="off"/>
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
            <div class="col-11 d-flex justify-content-end showCount" ><strong>Delivery Disponible:</strong> <label id="countDeliveries">{{$countDeliveries}}</label></div>       
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
                            <td width="100px">
                                <button class="btn btn-bottom" onClick="sendCode('{{$transaction->codeUrl}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Enviar Código"><i class="material-icons">send</i></button>
                                <button class="btn btn-bottom" id="btnAlarm" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--- Modal  -->
    <div class="modal fade" id="alarmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Alarma</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div class="justify-content-center has-success">
                        <div class="mb-3 row">
                            <label class="col-md-2 col-12  col-form-label">Fecha</label>
                            <div class="col">
                                <input type="text" class="form-control" name="alarm" id="alarm" value="{{Carbon::parse($endDate)->format('d/m/Y')}}" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-12  col-form-label">Hora</label>
                            <div class="col">
                                <input type="text" class="form-control" name="alarm" id="alarm" value="{{Carbon::parse($endDate)->format('d/m/Y')}}" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitAlarm" value="Guardar Alarma">
                        <div class="row marginAuto hide"id="loading">
                            <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    
    @include('admin.bookshopBottom')
    <script> 
        var statusMenu = "{{$statusMenu}}";
        $(".main-panel").perfectScrollbar('update');

        $(function () {

            $('#btnAlarm').on('click', function() {
                $('#alarmModal').modal('show');
            });
        });

        function sendCode(codeUrl){
            $.ajax({
                url: "{{route('admin.deliverySendCode')}}", 
                data: {"codeUrl" : codeUrl},
                type: "POST",
            }).done(function(data){
                if(data.status == 201){
                    alertify.success('Estado ha sido cambiado correctamente');
                    location.reload();
                }
                else
                    alertify.error('No hay delivery disponible');
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        }
    </script>
</body>
</html>