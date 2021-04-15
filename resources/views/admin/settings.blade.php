<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/settings.css') }}">
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/transactions.js') }}"></script>
    <script src="{{ asset('js/stateMunicipalities.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bookshop/jquery.maskMoney.min.js') }}"></script>
    
    <!-- libreria multi emails -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css//bookshop/jquery.multi-emails.css') }}">
    <script type="text/javascript" src="{{ asset('js//bookshop/jquery.multi-emails.js') }}"></script>
</head>
@php
    use Carbon\Carbon;
@endphp
<body class="body-admin">
    <div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')
        <div class="justify-content-center" id="row">
            <div class="col-10" style="margin-top:50px;">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings active" id="pills-delivery-tab" href="#pills-delivery" data-toggle="tab">Delivery</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-email-tab" href="#pills-email" data-toggle="tab">Correo Electrónico</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-cost-tab" href="#pills-cost" data-toggle="tab">Costo Delivery</a>
                    </li>
                </ul>
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-delivery" role="tabpanel" aria-labelledby="pills-delivery-tab">
                        <form id="formSchedule" class="contact-form" method='POST' action="{{route('admin.settingsSchedule')}}">
                            <div class="row">
                                <h4 class="mx-auto">Ingrese el horario de Delivery:</h4>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="row justify-content-center align-items-center minh-10">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-12  col-form-label pt-3">Horario</label>
                                    <div class="col">
                                        <label class="content-select">
                                            <select class="addMargin" name="hoursInitial" id="hoursInitial">
                                                @for ($hours=1; $hours<=12; $hours++) 
                                                    <option value="{{str_pad($hours,2,'0',STR_PAD_LEFT)}}">{{str_pad($hours,2,'0',STR_PAD_LEFT)}}</option>
                                                @endfor
                                            </select>
                                        </label>
                                        <label style="color:black; font-size: 30px; padding-left:10px;"> : </label>
                                        <label class="content-select">
                                            <select class="addMargin" name="minInitial" id="minInitial">
                                                @for ($mins=0; $mins<=59; $mins++) 
                                                    <option value="{{str_pad($mins,2,'0',STR_PAD_LEFT)}}">{{str_pad($mins,2,'0',STR_PAD_LEFT)}} </option>
                                                @endfor
                                            </select>
                                        </label>
                                        <label class="content-select">
                                            <select class="addMargin" name="anteMeridiemInitial" id="anteMeridiemInitial">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3 row divUntil">
                                    <span id="until"> Hasta </span>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col">
                                        <label class="content-select">
                                            <select class="addMargin" name="hoursFinal" id="hoursFinal">
                                                @for ($hours=1; $hours<=12; $hours++) 
                                                    <option value="{{str_pad($hours,2,'0',STR_PAD_LEFT)}}">{{str_pad($hours,2,'0',STR_PAD_LEFT)}}</option>
                                                @endfor
                                            </select>
                                        </label>
                                        <label style="color:black; font-size: 30px; padding-left:10px;"> : </label>
                                        <label class="content-select">
                                            <select class="addMargin" name="minFinal" id="minFinal">
                                                @for ($mins=0; $mins<=59; $mins++) 
                                                    <option value="{{str_pad($mins,2,'0',STR_PAD_LEFT)}}">{{str_pad($mins,2,'0',STR_PAD_LEFT)}} </option>
                                                @endfor
                                            </select>
                                        </label>
                                        <label class="content-select">
                                            <select class="addMargin" name="anteMeridiemFinal" id="anteMeridiemFinal">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-6 mx-auto">
                                    <button type="submit" class="submit btn btn-bottom" id="submitSchedule">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade has-success" id="pills-email" role="tabpanel" aria-labelledby="pills-email-tab">
                        <form id="formEmails" class="contact-form" method='POST' action="{{route('admin.settingsEmails')}}">    
                            <div class="row">
                                <h4 class="mx-auto">Ingrese la cuenta que recibirá el correo electrónico:</h4>
                            </div>
                            <div class="row">&nbsp;</div>
                            <p><strong>Importante:</strong> Separar correo con coma ( <strong>,</strong> ) o utilizando tecla tabulador (<strong>TAB</strong>) o barra espaciadora (<strong>SPACE</strong>)</p>
                            <div class="row">&nbsp;</div>
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12  col-form-label">Transacciones:</label>
                                <div class="col">
                                    <input class="form-control" type="text" id="emailsPaid" value="{{$emailsAllPaid? $emailsAllPaid->value : ''}}" autocomplete="off">
                                    <input type='hidden' id='emailsAllPaid' name='emailsAllPaid' class='form-control'>
                                    <!-- <textarea class="form-control" name="emailsPaid" id="emailsPaid" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>  
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12 col-form-label">Delivery</label>
                                <div class="col">
                                    <input class="form-control" type="text" id="emailsDelivery" value="{{$emailsAllDelivery? $emailsAllDelivery->value : ''}}" autocomplete="off">
                                    <input type='hidden' id='emailsAllDelivery' name='emailsAllDelivery' class='form-control'>
                                    <!-- <textarea class="form-control" name="emailsDelivery" id="emailsDelivery" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>  
                            <div class="mb-3 row">
                                <label class="col-md-2 col-12 col-form-label">Estado de pedido </label>
                                <div class="col">
                                    <input class="form-control" type="text" id="statusPaid" value="{{$statusPaidAll? $statusPaidAll->value : ''}}" autocomplete="off">
                                    <input type='hidden' id='statusPaidAll' name='statusPaidAll' class='form-control'>
                                    <!-- <textarea class="form-control" name="emailsDelivery" id="emailsDelivery" value="" placeholder="correo1@gmail.com; correo2@gmail.com" rows="5"></textarea> -->
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-6 mx-auto">
                                    <button type="submit" class="submit btn btn-bottom" id="submitEmail">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade has-success" id="pills-cost" role="tabpanel" aria-labelledby="pills-cost-tab">
                        <form id="formCost" action="{{route('admin.settingsCosts')}}" method="post">
                            <div class="row">
                                <h4 class="mx-auto">Ingrese el costo de Delivery:</h4>
                            </div>
                            <div class="row">
                                <label class="form"><strong>Estado:</strong></label>
                                <label class="content-select">
                                    <select class="addMargin" name="selectState" id="selectState" required="" data-parsley-required-message="Debe Seleccionar un Estado" >
                                        <option value="" selected>Seleccionar</option>
                                    </select>
                                </label>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row showTextMunicipalities">
                                <label class="form"><strong>Municipio:</strong></label>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div id="showCost" class="mx-auto row"></div>
                            <div class="row">&nbsp;</div>
                            <div class="row showTextMunicipalities" >
                            <div class="col-6 mx-auto">
                                    <button type="submit" class="submit btn btn-bottom" id="submitCost">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        $(".showTextMunicipalities").hide();
        var statusMenu = "{{$statusMenu}}";

        var hoursInitial = "{{$scheduleInitial['hours']}}";
        var minInitial = "{{$scheduleInitial['min']}}";
        var anteMeridiemInitial = "{{$scheduleInitial['anteMeridiem']}}";

        var hoursFinal = "{{$scheduleFinal['hours']}}";
        var minFinal = "{{$scheduleFinal['min']}}";
        var anteMeridiemFinal = "{{$scheduleFinal['anteMeridiem']}}";
        var MUNICIPALITIES, STATE, arrayMunicipalities, arrayState, listDeliveryCost;
;

        $("#hoursInitial option[value='"+ hoursInitial +"']").attr("selected",true);
        $("#minInitial option[value='"+ minInitial +"']").attr("selected",true);
        $("#anteMeridiemInitial option[value='"+ anteMeridiemInitial +"']").attr("selected",true);

        $("#hoursFinal option[value='"+ hoursFinal +"']").attr("selected",true);
        $("#minFinal option[value='"+ minFinal +"']").attr("selected",true);
        $("#anteMeridiemFinal option[value='"+ anteMeridiemFinal +"']").attr("selected",true);
        
        $.ajax({
            url: "{{ route('admin.listCost')}}", 
            type: "POST",
            dataType: "json"
        }).done(function(data){
            listDeliveryCost = data.list; 
            console.log(listDeliveryCost);
        }).fail(function(){  
            alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');               
        });

        $.ajax({
            'async': false,
            'global': false,
            'url': "{{ asset('json/state.json') }}",
            'dataType': "json",
            'success': function (data) {
                STATE = data;
                arrayState = State();
                arrayState.forEach(showState);
                /* $('#selectState option[value="Distrito Capital"]').attr('selected','selected'); */
            }
        });

        $.ajax({
            'async': false,
            'global': false,
            'url': "{{ asset('json/municipalities.json') }}",
            'dataType': "json",
            'success': function (data) {
                MUNICIPALITIES = data;
                /* arrayMunicipalities = Municipalities('Distrito Capital');
                console.log(arrayMunicipalities);
                arrayMunicipalities.forEach(showMunicipalities); */

            }
        }); 

        function showState(item, index) {
            if(item == 'Distrito Capital' || item == 'Miranda')
                $('#selectState').append('<option value="'+item+'">'+item+'</option>');
        }

        function showMunicipalities(item, index) {
            var indexList = listDeliveryCost.findIndex(x => x.state === $('#selectState').val() && x.municipalities === item);
            if(indexList >=0)
                $('#showCost').append('<div class="mb-3 col-md-6 col-12"><div class="row"><label class="col col-form-label">'+item+': </label><div class="col-6"><input type="hidden" name="listMunicipalities[]" value="'+item+'"> <input class="form-control deliveryCost" type="text" name="listCost[]" autocomplete="off" value="'+listDeliveryCost[indexList].cost+'"></div></div></div>');
            else
            $('#showCost').append('<div class="mb-3 col-md-6 col-12"><div class="row"><label class="col col-form-label">'+item+': </label><div class="col-6"><input type="hidden" name="listMunicipalities[]" value="'+item+'"> <input class="form-control deliveryCost" type="text" name="listCost[]" autocomplete="off"></div></div></div>');
        }

        $('#submitSchedule').on('click', function(e) {
            e.preventDefault();

            var hoursInitial = $('#hoursInitial').val();
            var minInitial = $('#minInitial').val();
            var anteMeridiemInitial = $('#anteMeridiemInitial').val();

            var hoursFinal = $('#hoursFinal').val();
            var minFinal = $('#minFinal').val();
            var anteMeridiemFinal = $('#anteMeridiemFinal').val();

            var d = new Date();
            var mounthToday = d.getMonth()+1;

            var scheduleInitial = Date.parse(d.getFullYear()+"/"+mounthToday+"/"+d.getDate()+" "+hoursInitial+":"+minInitial+" "+anteMeridiemInitial);
            var scheduleFinal = Date.parse(d.getFullYear()+"/"+mounthToday+"/"+d.getDate()+" "+hoursFinal+":"+minFinal+" "+anteMeridiemFinal);


            if(scheduleInitial < scheduleFinal){
                $( ".loader" ).fadeIn("slow"); 
                $('#formSchedule').submit();
            }
            else
                alertify.error('Debe seleccionar horario correctamente');
        });

        $('#submitEmail').on('click', function(e) {
            e.preventDefault();
            $( ".loader" ).fadeIn("slow"); 
            $('#formEmails').submit();
        });

        $('#submitCost').on('click', function(e) {
            e.preventDefault();
            $( ".loader" ).fadeIn("slow"); 
            $('#formCost').submit();
        });


        $(document).ready(function() {
            $("form").keypress(function(e) {
                if (e.which == 13) {
                    return false;
                }
            });

            $('#selectState').on('change', function() {
                $(".showTextMunicipalities").show();
                $('#showCost').html('');
                arrayMunicipalities = Municipalities(this.value);
                arrayMunicipalities.forEach(showMunicipalities);
                $(".deliveryCost").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'$ '});
            });

        });

        //Plug-in function for the bootstrap version of the multiple email
		$(function() {
			//To render the input device to multiple email input using BootStrap icon
			$('#emailsPaid').multiple_emails({position: "bottom"});
            $('#emailsDelivery').multiple_emails({position: "bottom"});
            $('#statusPaid').multiple_emails({position: "bottom"});
			
            $('#emailsAllPaid').val($('#emailsPaid').val());
			$('#emailsPaid').change( function(){
				$('#emailsAllPaid').val($(this).val());
			});

            $('#emailsAllDelivery').val($('#emailsDelivery').val());
			$('#emailsDelivery').change( function(){
				$('#emailsAllDelivery').val($(this).val());
			});

            $('#statusPaidAll').val($('#statusPaid').val());
			$('#statusPaid').change( function(){
				$('#statusPaidAll').val($(this).val());
			});
			
		});
		
    </script>
</body>
</html>