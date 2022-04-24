<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
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
    <script type="text/javascript" src="{{ asset('js/bookshop/jquery.multi-emails.js') }}"></script>
    <style>
        table, th, td {
            border-color: #3c4858 !important;
        }
    </style>
</head>
@php
    use Carbon\Carbon;
    $listDocument = array("R","J","G","C","V","E","P");
@endphp
<body class="body-admin">
    <div class="loader"></div>
    @include('auth.menu')
    <div class="main-panel">
        @include('auth.navbar')
        <div class="justify-content-center has-success" id="row">
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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-transfers-tab" href="#pills-transfers" data-toggle="tab">Transferencia</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-mobile-tab" href="#pills-mobile" data-toggle="tab">Pago Móvil</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-zelle-tab" href="#pills-zelle" data-toggle="tab">Zelle</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link settings" id="pills-cryptocurrencies-tab" href="#pills-cryptocurrencies" data-toggle="tab">Criptomonedas</a>
                    </li>
                </ul>
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="tab-content" id="pills-tabContent">
                    @include('admin.widgetSettings.delivery')
                    @include('admin.widgetSettings.email')
                    @include('admin.widgetSettings.cost')
                    @include('admin.widgetSettings.transfers')
                    @include('admin.widgetSettings.mobile')
                    @include('admin.widgetSettings.zelle')
                    @include('admin.widgetSettings.cryptocurrencies')
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
        var MUNICIPALITIES, STATE, arrayMunicipalities, arrayState, listDeliveryCost, BANKS, listBanks;

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
        }).fail(function(){  
            location.reload();            
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
                $('#showCost').append('<div class="mb-3 col-md-6 col-12"><div class="row"><label class="">'+item+': </label><div class="col-6"><input type="hidden" name="listMunicipalities[]" value="'+item+'"> <input class="form-control deliveryCost" type="text" name="listCost[]" autocomplete="off" required value="'+listDeliveryCost[indexList].cost+'"></div></div></div>');
            else
                $('#showCost').append('<div class="mb-3 col-md-6 col-12"><div class="row"><label class="">'+item+': </label><div class="col-6"><input type="hidden" name="listMunicipalities[]" value="'+item+'"> <input class="form-control deliveryCost" type="text" name="listCost[]" autocomplete="off" required></div></div></div>');
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

            var iTransfers, imobilePayment;

            if(parseInt('{{count($transfers)}}') == 0)
                iTransfers = 1;
            else
                iTransfers = parseInt('{{count($transfers)}}');

            if(parseInt('{{count($mobilePayments)}}') == 0)
                imobilePayment = 1;
            else
                imobilePayment = parseInt('{{count($mobilePayments)}}');


            $('#addTransfers').click(function(){
                iTransfers++;
                $('#dynamic_field_transfers').append('\
                    <tr id="rowTransfers'+iTransfers+'">\
                        <td>\
                            <div class="row">&nbsp;</div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Banco</label>\
                                <label class="content-select content-select-bank">\
                                    <select class="addMargin selectBank" name="bank[]" id="bank" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        @foreach($listBanks['Bank'] as $bank)\
                                            <option value="{{$bank}}">{{$bank}}</option>\
                                        @endforeach\
                                    </select>\
                                </label>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Titular:</label>\
                                <div class="col">\
                                    <input class="form-control" type="text" name="accountName[]" autocomplete="off" placeholder="Joe Doe" minlength="4" required>\
                                </div>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Cédula o Rif</label>\
                                <label class="content-select">\
                                    <select class="addMargin" name="typeCard[]" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        @foreach($listDocument as $type)\
                                            <option value="{{$type}}">{{$type}}</option>\
                                        @endforeach\
                                    </select>\
                                </label>\
                                <span style="padding-top: 7px;">&nbsp;&nbsp; - &nbsp;&nbsp;</span>\
                                <div class="col">\
                                    <input type="number" name="idCard[]" class="form-control" minlength="4" required/>\
                                </div>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Número de Cuenta</label>\
                                <div class="col">\
                                    <input class="form-control" type="number" name="accountNumber[]" autocomplete="off" placeholder="010222222222" minlength="19" maxlength="20" required>\
                                </div>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Tipo de Cuenta</label>\
                                <label class="content-select">\
                                    <select class="addMargin" name="accountType[]" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        <option value="A">Ahorro</option>\
                                        <option value="C">Corriente</option>\
                                    </select>\
                                </label>\
                            </div>\
                        </td>\
                        <td style="text-align: center; vertical-align: middle; border-left-color: transparent !important; border-left-width: 2px; border-left-style: solid;">\
                            <button type="button" name="remove" id="'+iTransfers+'" class="btn btn-danger btn_remove" data-type="0"><i class="fa fa-trash" aria-hidden="true"></i></button>\
                        </td>\
                    </tr>\
                ');
            });

            $('#addMobile').click(function(){
                imobilePayment++;
                $('#dynamic_field_mobile').append('\
                    <tr id="rowMobile'+imobilePayment+'">\
                        <td>\
                            <div class="row">&nbsp;</div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Banco</label>\
                                <label class="content-select content-select-bank">\
                                    <select class="addMargin selectBank" name="bank[]" id="bank" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        @foreach($listBanks['Bank'] as $bank)\
                                            <option value="{{$bank}}">{{$bank}}</option>\
                                        @endforeach\
                                    </select>\
                                </label>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Cédula</label>\
                                <div class="col">\
                                    <input class="form-control" type="number" name="idCard[]" autocomplete="off" placeholder="22222222" minlength="4" required>\
                                </div>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Número de Teléfono</label>\
                                <div class="col">\
                                    <input class="form-control" type="tel" name="phone[]" autocomplete="off" placeholder="04125555555" size="11" maxlength="11" pattern="^(0414|0424|0412|0416|0426)[0-9]{7}$" required>\
                                </div>\
                            </div>\
                        </td>\
                        <td style="text-align: center; vertical-align: middle; border-left-color: transparent !important; border-left-width: 2px; border-left-style: solid;">\
                            <button type="button" name="remove" id="'+imobilePayment+'" class="btn btn-danger btn_remove" data-type="1"><i class="fa fa-trash" aria-hidden="true"></i></button>\
                        </td>\
                    </tr>\
                ');
            });

            $(document).on('click', '.btn_remove', function(){
                var type = $(this).data("type");
                var button_id = $(this).attr("id"); 

                console.log(button_id);

                if(type == 0)
                    $('#rowTransfers'+button_id+'').remove();
                else
                    $('#rowMobile'+button_id+'').remove();
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

        function showWallet(id)
        {
            $.ajax({
                url: "{{route('admin.showWallet')}}", 
                data: {"id" : id},
                type: "POST",
            }).done(function(data){
                $('#showWallet').html(data.html);
                $('#walletModal').modal('show'); 
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#walletModal').modal('hide'); 
                $('#showWallet').html();
            });
        }
		
    </script>
</body>
</html>