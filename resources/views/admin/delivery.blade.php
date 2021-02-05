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

                                <label class="col-md-2 col-12 col-form-label">Código</label>
                                <div class="col">
                                    <input type="text" class="form-control" name="searchCodeUrl" id="searchCodeUrl" value="{{$searchCodeUrl}}">
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
                <table id="table_id" class="table table-bordered mb-5 display" width="100%">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">#</th>
                            <th scope="col">Nombre Compañia</th>
                            <th scope="col">Código</th> 
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
                            <td>{{ $transaction->codeUrl}}</td> 
                            <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                            <td>{{$transaction->selectShipping}}</td>
                            <td>@if($transaction->idDelivery != null) <div class="sendDelivery">Enviado</div> @else  <div class="pendingDelivery">Pendiente</div> @endif </td>
                            <td>@if($transaction->alarm) <div class="activatedAlarm">Activado</div> @else <div class="disabledAlarm">Desactivado</div> @endif</td>
                            <td width="100px">
                                <button class="btn btn-bottom" onClick="sendCode('{{$transaction->codeUrl}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Enviar Código"><i class="material-icons">send</i></button>
                                @if($transaction->alarm)
                                    <button class="btn btn-bottom" id="btnAlarm" onClick="showAlarm('{{$transaction->id}}', '{{Carbon::parse($transaction->alarm)->format('d/m/Y')}}', {{Carbon::parse($transaction->alarm)->format('g')}}, {{Carbon::parse($transaction->alarm)->format('i')}}, '{{Carbon::parse($transaction->alarm)->format('A')}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                                @else
                                    <button class="btn btn-bottom" id="btnAlarm" onClick="showAlarm('{{$transaction->id}}', '{{$endDate}}', 1, 0, 'AM')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                                @endif
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
                                <input type="text" class="form-control" name="dateAlarm" id="dateAlarm" value="{{Carbon::parse($endDate)->format('d/m/Y')}}" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-12  col-form-label">Hora</label>
                            <div class="col">
                                <label class="content-select">
                                    <select class="addMargin" name="hours" id="hours">
                                        @for ($hours=1; $hours<=12; $hours++) 
                                            <option value="{{str_pad($hours,2,'0',STR_PAD_LEFT)}}">{{str_pad($hours,2,'0',STR_PAD_LEFT)}}</option>
                                        @endfor
                                    </select>
                                </label>
                                <label style="color:black; font-size: 30px; padding-left:10px;"> : </label>
                                <label class="content-select">
                                    <select class="addMargin" name="min" id="min">
                                        @for ($mins=0; $mins<=59; $mins++) 
                                            <option value="{{str_pad($mins,2,'0',STR_PAD_LEFT)}}">{{str_pad($mins,2,'0',STR_PAD_LEFT)}} </option>
                                        @endfor
                                    </select>
                                </label>
                                <label class="content-select">
                                    <select class="addMargin" name="anteMeridiem" id="anteMeridiem">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitAlarm" value="Guardar Alarma">
                        <div class="row marginAuto hide"id="loading">
                            <img widht="80px" height="80px" class="justify-content-center" src="asset('images/loading.gif')">
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
        var idSelect;
        $(".main-panel").perfectScrollbar('update');

        window.Echo.channel('channel-ctpagaDeliveryStatus').listen('.event-ctpagaDeliveryStatus', (data) => {
            alert("entro");
            $.ajax({
                url: "{{route('admin.countDeliveries')}}", 
                type: "POST",
            }).done(function(data){
                if(data.status == 201){
                    $('#countDeliveries').text(data.count);
                }

            }).fail(function(result){});
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

        function showAlarm(id, date, hours, min, anteMeridiem){
            idSelect = id;
            $('#dateAlarm').val(date);
            $("#hours option[value='"+ hours +"']").attr("selected",true);
            $("#min option[value='"+ min +"']").attr("selected",true);
            $("#anteMeridiem option[value='"+ anteMeridiem +"']").attr("selected",true);
            $('#alarmModal').modal('show');
        }

        
        $(function () {
            $('#submitAlarm').on('click', function() {
                var dateAlarm = $('#dateAlarm').val();
                var hours = $('#hours').val();
                var min = $('#min').val();
                var anteMeridiem = $('#anteMeridiem').val();
                var dateAlarmJS = dateAlarm + " a la " + hours +" : "+ min + " " + anteMeridiem;
                var dateAlarmPHP = dateAlarm + " " + hours +":"+ min + " " + anteMeridiem;
                alertify.confirm('Confirmar Alarma', 'Activar alarma el '+dateAlarmJS, function(){
                    $('#submitAlarm').hide();
                    $('#loading').removeClass("hide");
                    $('#loading').addClass("show");
                    console.log(idSelect);
                    console.log(dateAlarmPHP);
                    $.ajax({
                        url: "{{route('admin.saveAlarm')}}", 
                        data: {"id": idSelect, "dateAlarm": dateAlarmPHP},
                        type: "POST",
                    }).done(function(result){
                        $('#alarmModal').modal('hide');
                        if(result.status == 201){
                            alertify.success("Guardado Correctamente!");
                            location.reload();
                        }
                    }).fail(function(result){
                        $('#submitAlarm').show();
                        $('#loading').removeClass("show");
                        $('#loading').addClass("hide");
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    });
                }, function(){});
            });

        });
    </script>
</body>
</html>