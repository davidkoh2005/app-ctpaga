<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
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
                    <div class="tab-pane fade has-success" id="pills-transfers" role="tabpanel" aria-labelledby="pills-transfers-tab">
                        <form id="formTransfers" action="{{route('admin.settingsTransfers')}}" method="POST" data-toggle="validator" role="form">
                            <div class="row">
                                <h4 class="mx-auto"><strong>Transferencia:</strong></h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field_transfers" bordercolor="#ff0000" style="width:99% !important;">
                                    <tr>
                                        <td colspan="2"><button type="button" name="add" id="addTransfers" class="btn btn-bottom">Agregar Nueva Transferencia</button></td>
                                    </tr>
                                    @if(count($transfers) >0)
                                        @foreach($transfers as $key=>$transfer)
                                            @if($transfer->type == 0)
                                                <input type="hidden" name="allTransfers[]" value="{{$transfer->id}}">
                                                <tr id="rowTransfers{{intval($key)+1}}">
                                                    @if($key == 0)
                                                        <td colspan="2">
                                                    @else
                                                        <td>
                                                    @endif
                                                        <div class="row">&nbsp;</div>
                                                        <input type="hidden" name="idTransfers[]" value="{{$transfer->id}}">
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Banco</label>
                                                            <label class="content-select content-select-bank">
                                                                <select class="addMargin selectBank" name="bank[]" id="bank" required>
                                                                    <option value="" disabled>Seleccionar</option>
                                                                    @foreach($listBanks['Bank'] as $bank)
                                                                        @if($bank == $transfer->bank)
                                                                            <option value="{{$bank}}" selected>{{$bank}}</option>
                                                                        @else
                                                                            <option value="{{$bank}}">{{$bank}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Titular:</label>
                                                            <div class="col">
                                                                <input class="form-control" type="text" name="accountName[]" autocomplete="off" placeholder="Joe Doe" minlength="4" value="{{$transfer->accountName}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Cédula o Rif</label>
                                                            <label class="content-select">
                                                                <select class="addMargin" name="typeCard[]" required>
                                                                    <option value="" disabled>Seleccionar</option>
                                                                    @foreach($listDocument as $type)
                                                                        @if($type == substr($transfer->idCard,0,1))
                                                                            <option value="{{$type}}" selected>{{$type}}</option>
                                                                        @else
                                                                            <option value="{{$type}}">{{$type}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                            <span style="padding-top: 7px;">&nbsp;&nbsp; - &nbsp;&nbsp;</span>
                                                            <div class="col">
                                                                <input type="number" name="idCard[]" class="form-control" minlength="4" value="{{substr($transfer->idCard,2)}}" required/>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Número de Cuenta</label>
                                                            <div class="col">
                                                                <input class="form-control" type="number" name="accountNumber[]" autocomplete="off" placeholder="010222222222" minlength="19" maxlength="20" value="{{$transfer->accountNumber}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Tipo de Cuenta</label>
                                                            <label class="content-select">
                                                                <select class="addMargin" name="accountType[]" required>
                                                                    <option value="" disabled>Seleccionar</option>
                                                                    @if($transfer->accountType == "A")
                                                                        <option value="A" selected>Ahorro</option>
                                                                        <option value="C">Corriente</option>
                                                                    @else
                                                                        <option value="A">Ahorro</option>
                                                                        <option value="C" selected>Corriente</option>
                                                                    @endif
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(count($transfers) != 0 && $key != 0)
                                                        <td style="text-align: center; vertical-align: middle; border-left-color: transparent !important; border-left-width: 2px; border-left-style: solid;">
                                                            <button type="button" name="remove" id="{{$key+1}}" class="btn btn-danger btn_remove" data-type="0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr id="rowTransfers1">
                                            <td colspan="2">
                                                <div class="row">&nbsp;</div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Banco</label>
                                                    <label class="content-select content-select-bank">
                                                        <select class="addMargin selectBank" name="bank[]" id="bank" required>
                                                            <option value="" disabled selected>Seleccionar</option>
                                                            @foreach($listBanks['Bank'] as $bank)
                                                                <option value="{{$bank}}">{{$bank}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Titular:</label>
                                                    <div class="col">
                                                        <input class="form-control" type="text" name="accountName[]" autocomplete="off" placeholder="Joe Doe" minlength="4" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Cédula o Rif</label>
                                                    <label class="content-select">
                                                        <select class="addMargin" name="typeCard[]" required>
                                                            <option value="" disabled selected>Seleccionar</option>
                                                            @foreach($listDocument as $type)
                                                                <option value="{{$type}}">{{$type}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                    <span style="padding-top: 7px;">&nbsp;&nbsp; - &nbsp;&nbsp;</span>
                                                    <div class="col">
                                                        <input type="number" name="idCard[]" class="form-control" minlength="4" required/>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Número de Cuenta</label>
                                                    <div class="col">
                                                        <input class="form-control" type="number" name="accountNumber[]" autocomplete="off" placeholder="010222222222" minlength="19" maxlength="20" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Tipo de Cuenta</label>
                                                    <label class="content-select">
                                                        <select class="addMargin" name="accountType[]" required>
                                                            <option value="" disabled selected>Seleccionar</option>
                                                            <option value="A">Ahorro</option>
                                                            <option value="C">Corriente</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="col-6 mx-auto">
                                <button type="submit" class="submit btn btn-bottom" id="submitTransfers">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-mobile" role="tabpanel" aria-labelledby="pills-mobile-tab">
                        <form id="formMobile" action="{{route('admin.settingsMobile')}}" method="POST" data-toggle="validator" role="form">

                            <div class="row">
                                <h4 class="mx-auto"> <strong>Pago móvil:</strong></h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field_mobile" bordercolor="#ff0000" style="width:99% !important;">
                                    <tr>
                                        <td colspan="2"><button type="button" name="add" id="addMobile" class="btn btn-bottom">Agregar Nuevo Pago Móvil</button></td>
                                    </tr>
                                    @if(count($mobilePayments) > 0)
                                        @foreach($mobilePayments as $key=>$mobilePayment)
                                            @if($mobilePayment->type == 1)
                                                <input type="hidden" name="allMobilePayments[]" value="{{$mobilePayment->id}}">
                                                <tr id="rowMobile{{intval($key)+1}}">
                                                    @if($key == 0)
                                                        <td colspan="2">
                                                    @else
                                                        <td>
                                                    @endif
                                                    <div class="row">&nbsp;</div>
                                                    <input type="hidden" name="idMobile[]" value="{{$mobilePayment->id}}">
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Banco</label>
                                                            <label class="content-select content-select-bank">
                                                                <select class="addMargin selectBank" name="bank[]" id="bank" required>
                                                                    <option value="" disabled selected>Seleccionar</option>
                                                                    @foreach($listBanks['Bank'] as $bank)
                                                                        @if($bank == $mobilePayment->bank)
                                                                            <option value="{{$bank}}" selected>{{$bank}}</option>
                                                                        @else
                                                                            <option value="{{$bank}}">{{$bank}}</option>
                                                                        @endif
                                                                    @endforeach                                                                
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Cédula</label>
                                                            <div class="col">
                                                                <input class="form-control" type="number" name="idCard[]" autocomplete="off" placeholder="22222222" minlength="4" value="{{$mobilePayment->idCard}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 row">
                                                            <label class="col-sm-4 col-form-label">Número de Teléfono</label>
                                                            <div class="col">
                                                                <input class="form-control" type="tel" name="phone[]" autocomplete="off"  placeholder="04125555555" size="11" maxlength="11" pattern="^(0414|0424|0412|0416|0426)[0-9]{7}$" value="{{$mobilePayment->phone}}" required>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @if(count($mobilePayments) != 0 && $key != 0)
                                                    <td style="text-align: center; vertical-align: middle; border-left-color: transparent !important; border-left-width: 2px; border-left-style: solid;">
                                                            <button type="button" name="remove" id="{{$key+1}}" class="btn btn-danger btn_remove" data-type="1"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr id="rowMobile1">
                                            <td colspan="2">
                                                <div class="row">&nbsp;</div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Banco</label>
                                                    <label class="content-select content-select-bank">
                                                        <select class="addMargin selectBank" name="bank[]" required>
                                                            <option value="" disabled selected>Seleccionar</option>
                                                            @foreach($listBanks['Bank'] as $bank)
                                                                <option value="{{$bank}}">{{$bank}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Cédula</label>
                                                    <div class="col">
                                                        <input class="form-control" type="number" name="idCard[]" autocomplete="off" placeholder="22222222" minlength="4" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-4 col-form-label">Número de Teléfono</label>
                                                    <div class="col">
                                                        <input class="form-control" type="number" name="phone[]" autocomplete="off" placeholder="04125555555" size="11" maxlength="11" pattern="^(?:(\+)58|0)(?:2(?:12|4[0-9]|5[1-9]|6[0-9]|7[0-8]|8[1-35-8]|9[1-5]|3[45789])|4(?:1[246]|2[46]))\d{7}$" required>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="col-6 mx-auto">
                                <button type="submit" class="submit btn btn-bottom" id="submitMobile">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade has-success" id="pills-zelle" role="tabpanel" aria-labelledby="pills-zelle-tab">
                        <form id="formCost" action="{{route('admin.settingsZelle')}}" method="post">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Correo electronico</label>
                                <div class="col">
                                    <input class="form-control" type="email" name="email" autocomplete="off"  minlength="4" value="{{$zelle != NULL ? $zelle->value : ''}}" required>
                                </div>
                            </div>
                            <div class="col-6 mx-auto">
                                <button type="submit" class="submit btn btn-bottom" id="submitCost">Guardar</button>
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
		
    </script>
</body>
</html>