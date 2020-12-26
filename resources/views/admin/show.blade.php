<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../../css/show.css">
    <link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>
    <script type="text/javascript" src="../../js/show.js"></script>
    <script type="text/javascript" src="../../js/rotate.js"></script>
    <script type="text/javascript" src="../../js/datatables.min.js"></script>
</head>
<body>
    @include('admin.navbar')
    <section>
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="card text-center">
                    <div class="card-header">
                        Selfie
                    </div>
                    <div class="card-body center">
                        @if($selfie)
                            @php
                             $idSelfie = $selfie->id;
                            @endphp
                            <div class="zoom">
                                <img src="{{url($selfie->url)}}" width="250px" height="350px">
                            </div>
                        @else
                            @php
                             $idSelfie = 0;
                            @endphp
                            <p> No tiene Foto </p>
                        @endif
                    </div>
                    @if($selfie)
                    <div class="card-footer ">
                        <input type="button" class="btn btn-bottom btn-remove" id="rejectSelfie" value="Rechazar">
                    </div>
                    @endif
                </div>
                <div class="row">&nbsp;</div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card text-center">
                    <div class="card-header">
                        Informacion
                    </div>
                    <div class="card-body">
                        <h5 class="center">Datos Personal</h5>
                        <label><strong>Nombre: </strong>{{$user->name}}</label> <br>
                        <label><strong>Telefono: </strong>{{$user->phone}}</label> <br>
                        <label><strong>Dirección: </strong>{{$user->address}}</label> <br>
                        <label><strong>Correo: </strong>{{$user->email}}</label> 

                        <div class="row">&nbsp;</div>

                        <h5 class="center">Datos de Empresa</h5>
                        <label><strong>Nombre: </strong>{{$commerce->name}}</label> <br>
                        <label><strong>Rif: </strong>{{$commerce->rif}}</label> <br>
                        <label><strong>Telefono: </strong>{{$commerce->phone}}</label> <br>
                        <label><strong>Dirección: </strong>{{$commerce->address}}</label> <br>
                        <label><strong>Link: </strong><a href="{{route('form.store', ['userUrl' => $commerce->userUrl])}}" class="tienda">Tienda</a></label> <br>

                    </div>
                </div>
                <div class="row">&nbsp;</div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card text-center">
                    <div class="card-header">
                        Balance
                    </div>
                    <div class="card-body ">
                        <h1 class="center"> @if($balance->coin == 0) $ @else BS @endif {{$balance->total}} </h1>
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        @if($selfie && count($pictures) == 2 && $balance->total >0)
                            <input type="button" class="btn pay btn-bottom btn-current" value="Pagar">
                        @else
                            <input type="button" class="btn btn-error" value="No puede realizar pago">
                        @endif
                        <div class="row">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="row">
            @foreach ($pictures as $picture)
                <div class="col-md-6 col-12 card text-center">
                    <div class="card-header">
                        @if($picture->descripction == 'Identification')
                            Identificación
                        @else
                            {{$picture->description}}
                        @endif
                    </div>
                    <div class="card-body center">
                        <div class="zoom">
                            <img src="{{url($picture->url)}}" width="250px" height="350px">
                        </div>
                    </div>
                    <div class="card-footer btnReject">
                        <input type="button" class="btn btn-bottom btn-remove" value="Rechazar">
                        <input type="hidden" name="idPictures" id="idPictures" value="{{$picture->id}}">
                    </div>
                </div>
                <div class="row">&nbsp;</div>
            @endforeach
        </div>
    </section>


    <!--- Modal Picture -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <img src="" class="imagepreview" >
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <button type="button" id="left" class="btn btn-bottom btn-current">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                        </button>
                        <button type="button" id="right" class="btn btn-bottom btn-current">
                            <i class="fa fa-repeat" aria-hidden="true"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--- Modal Pay-->
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <label><strong>Datos del Bancaria:</strong></label>
                    <div class="dataPay">
                        @if($balance->coin == 0)

                            <label><strong>País: </strong>{{$bank->country}}</label> <br> 
                            <label><strong>Nombre de la cuenta: </strong>{{$bank->accountName}}</label> <br> 
                            <label><strong>Número de la cuenta: </strong>{{$bank->accountNumber}}</label> <br> 
                            <label><strong>Nombre del banco: </strong>{{$bank->bankName}}</label> <br> 
                            @if($bank->country == "USA")
                                <label><strong>Ruta o Aba: </strong>{{$bank->route}}</label> <br> 
                            @endif
                            <label><strong>Dirección: </strong>{{$bank->address}}</label> <br> 
                            <label><strong>Tipo de Cuenta: </strong>{{$bank->accountType}}</label> <br> 
                        @else
                            
                            <label><strong>País: </strong>{{$bank->country}}</label> <br> 
                            <label><strong>NUmero de cédula: </strong>{{$bank->idCard}}</label> <br> 
                            <label><strong>Nombre de la cuenta: </strong>{{$bank->accountName}}</label> <br> 
                            <label><strong>Número de la cuenta: </strong>{{$bank->accountNumber}}</label> <br> 
                            <label><strong>Nombre del banco: </strong>{{$bank->bankName}}</label> <br> 
                            <label><strong>Dirección: </strong>{{$bank->address}}</label> <br> 
                            <label><strong>Tipo de Cuenta: </strong>{{$bank->accountType}}</label> <br>
                        @endif
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <label><strong>Datos del Pago:</strong></label>
                    <div class="dataPay">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="refPay" classs="col-form-label">Nº Referencia del Pago:</label>
                            </div>
                            <div class="col-auto">
                                <input type="tel" name="numRef"  id="numRef" class="form-control" placeholder="123456789" />
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="ammount" classs="col-form-label">Monto:</label>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <div class="input-group-text">@if($balance->coin == 0) $ @else Bs @endif</div>
                                    <input type="tel" name="amount" id="amount" class="form-control" value="{{$balance->total}}" readonly/>
                                </div>
                            </div>
                        </div>
                    </div>              
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submit" value="Guardar Referencia">
                        <div class="row marginAuto"id="loading">
                            <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--- Modal Picture -->
    <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>              
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Razón:</strong></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submitReason" value="Enviar Razón">
                        <div class="row marginAuto"id="loadingReason">
                            <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        $('#loading').hide();
        $('#loadingReason').hide();
        var idSelfie = {{$idSelfie}};
        var idBalance = {{$balance->id}};
        var totalBalance = {{$balance->total}};
        var statusReason= 0;
        var idSelect;
    
        $(function() {
            $('#rejectSelfie').on('click', function() {
                statusReason= 0;
                $('#reasonModal').modal('show'); 
            });	


            $('.btnReject').on('click', function() {
                statusReason= 1;
                idSelect = $(this).find('#idPictures').val();
                $('#reasonModal').modal('show'); 
            });
            
            $('#submitReason').on('click', function() {
                var reason = $('#reason').val();
                console.log(reason);
                if(!reason){
                    alertify.error('Debe ingresar alguna razón');
                }else{
                    $('#submitReason').hide();
                    $('#loadingReason').show();
                    if(statusReason == 0){
                        $.ajax({
                            url: "{{route('admin.removePicture')}}", 
                            data: {"id" : idSelfie, "reason": reason},
                            type: "POST",
                        }).done(function(result){
                            if(result.status == 201){
                                location.reload();
                            }
                        }).fail(function(result){});
                    }else{
                        $.ajax({
                            url: "{{route('admin.removePicture')}}", 
                            data: {"id" : idSelect,  "reason": reason},
                            type: "POST",
                        }).done(function(result){
                            $('#reasonModal').modal('hide'); 
                            if(result.status == 201){
                                location.reload();
                            }
                        }).fail(function(result){});
                    }
                }
            });	
            

            $('.pay').on('click', function() {
                $('#payModal').modal('show');  
            });	

            $('#submit').on('click', function() {
                var status = true;
                var numRef = $('#numRef').val();
                $('#payModal').modal('show'); 

                if(numRef.length <11){
                    status = false;
                    alertify.error('Debe ingresar el numero de referencia correctamente');
                }

                if(status){
                    $('#submit').hide();
                    $('#loading').show();
                    $.ajax({
                        url: "{{route('admin.saveDeposits')}}", 
                        data: {"id" : idBalance, "numRef": numRef, "total": totalBalance},
                        type: "POST",
                    }).done(function(result){
                        $('#payModal').modal('hide');  
                        if(result.status == 201){
                            location.reload();
                        }
                    }).fail(function(result){});
                }
                
            });	
        });	
    </script>
</body>
</html>