<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
</head>
<body class="body-admin">
<div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')
        @php
            use Carbon\Carbon;
        @endphp
        <div class="justify-content-center" id="row">
             <div class="col-10">
                <div class="card card-Transactions">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success" style="margin:15px;">
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('admin.deliverySearch')}}">  
                        <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Buscar Estado</label>
                                <label class="content-select">
                                    <select class="addMargin" name="searchStatus" id="searchStatus">
                                        <option value="0" disabled>Estado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Publicado</option>
                                        <option value="3">Urgente</option>
                                        <option value="4">En tránsito</option>
                                        <option value="5">Producto Retirado</option>
                                    </select>
                                </label>
                            </div>

                            <div class="row">&nbsp;</div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                                <div class="col-6">
                                    <a type="button" class="remove-transactions btn" href="{{route('admin.delivery')}}">Limpiar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="col-12">
            <div class="tableShow">
                <table id="table_id" class="table table-bordered display" style="width:100%;">
                    <thead>
                        <tr class="table-title">
                            <th scope="col">Ver</th>
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
                            <td scope="row">
                                <button class="btn btn-bottom"onClick="showPaid({{$transaction->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Pedido"><i class="material-icons">visibility</i></button>
                            </td>
                            <td >{{ $transaction->id }}</td>
                            <td>{{ $transaction->name }}</td>
                            <td>{{ $transaction->codeUrl}}</td> 
                            <td> {{date('d/m/Y h:i A',strtotime($transaction->date))}}</td>
                            <td>{{$transaction->selectShipping}}</td>
                            <td>
                                @if($transaction->statusDelivery==1 && $transaction->timeDelivery != null && $transaction->timeDelivery <= Carbon::now()) 
                                    <div class="urgentDelivery">Urgente</div> 
                                @elseif($transaction->statusDelivery == 0) 
                                    <div class="pendingDelivery">Pendiente</div> 
                                @elseif($transaction->statusDelivery == 1) 
                                    <div class="publicDelivery">Publicado</div> 
                                @elseif($transaction->statusShipping == 0) 
                                    <div class="inTransit">En tránsito</div>  
                                @elseif($transaction->statusShipping == 1) 
                                    <div class="RetiredProduct">Producto Retirado</div>  
                                @endif 
                            </td>
                            <td>@if($transaction->alarm) <div class="activatedAlarm">Activado</div> @else <div class="disabledAlarm">Desactivado</div> @endif</td>
                            <td width="100px">
                                <button class="btn btn-bottom" onClick="publicCode('{{$transaction->codeUrl}}', '{{$transaction->statusDelivery}}', '{{$transaction->statusDelivery==1 && $transaction->timeDelivery != null && $transaction->timeDelivery <= Carbon::now()}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Publicar Orden"><i class="material-icons">send</i></button>
                                @if($transaction->alarm)
                                    <button class="btn btn-bottom" id="btnAlarm" onClick="showAlarm('{{$transaction->id}}', '{{Carbon::parse($transaction->alarm)->format('d/m/Y')}}', {{Carbon::parse($transaction->alarm)->format('g')}}, {{Carbon::parse($transaction->alarm)->format('i')}}, '{{Carbon::parse($transaction->alarm)->format('A')}}', '{{$transaction->idDelivery}}', '{{$transaction->statusDelivery}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                                @else
                                    <button class="btn btn-bottom" id="btnAlarm" onClick="showAlarm('{{$transaction->id}}', '{{Carbon::parse($endDate)->format('d/m/Y')}}', 1, 0, 'AM', '{{$transaction->idDelivery}}', '{{$transaction->statusDelivery}}')" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Recordatorio"><i class="material-icons">alarm</i></button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="showDelivery" class="tableShow"></div>
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
                                <input type="text" class="form-control" name="dateAlarm" id="dateAlarm" value="{{Carbon::parse($endDate)->format('d/m/Y')}}" />
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
                            <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loadingTransparent.gif').'?v='.time()   }}">
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
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
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        var searchStatus ='{{$searchStatus}}';
        $("#searchStatus option[value='"+ searchStatus +"']").prop("selected",true);

        var idSelect;
        $(".main-panel").perfectScrollbar('update');

        function showPaid(id)
        {
            $( ".loader" ).fadeIn("slow"); 
            $.ajax({
                url: "{{route('admin.transactionsShow')}}", 
                data: {"id" : id},
                type: "GET",
            }).done(function(data){
                $( ".loader" ).fadeOut("slow"); 
                $('#productsModal').modal('show'); 
                $('#showProducts').html(data.html);
            }).fail(function(result){
                $( ".loader" ).fadeOut("slow"); 
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#productsModal').modal('hide'); 
                $('#showProducts').html();
            });
        }

        function publicCode(codeUrl, idDelivery, status){
            if(parseInt(idDelivery) == 1 && status || parseInt(idDelivery) == 1 && !status){
                //$( ".loader" ).fadeIn("slow"); 
                var urlphp = "{{url('/admin/delivery/')}}";
                var urlphp = urlphp.concat("/");

                window.location.replace(urlphp.concat(codeUrl));
                /* $.ajax({
                    url: "{{route('admin.showDeliveryAjax')}}", 
                    data: {"codeUrl" : codeUrl},
                    type: "POST",
                    }).done(function(data){
                        if(data.status == 201){
                            window.location.replace(data.url);
                        }
                        else{
                            $( ".loader" ).fadeOut("slow"); 
                            alertify.error('No hay delivery disponible');
                        }
                    }).fail(function(result){
                        $( ".loader" ).fadeOut("slow"); 
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }); */
            }else if(parseInt(idDelivery) == 2)
                alertify.error('Esta transacción ya fue ordenado');
            else{
                $( ".loader" ).fadeIn("slow"); 
                $.ajax({
                    url: "{{route('admin.deliverySendCode')}}", 
                    data: {"codeUrl" : codeUrl},
                    type: "POST",
                }).done(function(data){
                    if(data.status == 201){
                        $( ".loader" ).fadeOut("slow"); 
                        alertify.success('Se ha publicado correctamente');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                    else{
                        $( ".loader" ).fadeOut("slow"); 
                        alertify.error('No hay delivery disponible');
                    }
                }).fail(function(result){
                    $( ".loader" ).fadeOut("slow"); 
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                }); 
            }
        }

        function showAlarm(id, date, hours, min, anteMeridiem, idDelivery, status){
            if(parseInt(status) == 0){
                idSelect = id;
                if(parseInt(idDelivery) > 0)
                    alertify.error('No se puede agregar alarma porque esta transacción ya fue enviado');
                else{
                    $('#dateAlarm').val(date);
                    $("#hours option[value='"+ hours +"']").attr("selected",true);
                    $("#min option[value='"+ min +"']").attr("selected",true);
                    $("#anteMeridiem option[value='"+ anteMeridiem +"']").attr("selected",true);
                    $('#alarmModal').modal('show');
                } 
            }else{
                alertify.error('No puede activar alarma!');
            }

        }

        
        $(function () {
            $('#submitAlarm').on('click', function() {
                var dateAlarm = $('#dateAlarm').val();
                var hours = $('#hours').val();
                var min = $('#min').val();
                var anteMeridiem = $('#anteMeridiem').val();
                var dateAlarmJS = dateAlarm + " a la " + hours +" : "+ min + " " + anteMeridiem;
                var dateAlarmPHP = dateAlarm + " " + hours +":"+ min + " " + anteMeridiem;
                dateAlarmPHP = dateAlarmPHP.replaceAll("/","-");
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
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
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