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
                @if($statusID)
                    <label><strong>Datos del Bancaria:</strong></label>
                    <div class="dataPay">
                        @if($deposit->coin == 0)

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
                @endif
                    <label><strong>Datos del Pago:</strong></label>
                    <div class="dataPay has-success">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="refPay" classs="col-form-label">Nº Referencia del Pago:</label>
                            </div>
                            <div class="col-auto">
                                <input type="tel" name="numRef"  id="numRef" class="form-control" placeholder="123456789" />
                            </div>
                        </div>
                        @if($statusID)
                        <div class="row">&nbsp;</div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="ammount" classs="col-form-label">Monto:</label>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <div class="input-group-text">@if($deposit->coin == 0) $ @else Bs @endif</div>
                                    <input type="tel" name="amount" id="amount" class="form-control" value="{{$deposit->total}}" readonly/>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>              
                </div>
                <div class="modal-footer">
                    <div class="marginAuto">
                        <input type="input" class="btn btn-bottom btn-current" id="submit" value="Guardar Referencia">
                        <div class="row marginAuto hide"id="loading">
                            <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loading.gif') }}">
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(document).ready( function () {
            $('#submit').on('click', function() {
                var status = true;
                var numRef = $('#numRef').val();

               if(numRef.length <11){
                    status = false;
                    alertify.error('Debe ingresar el numero de referencia correctamente');
                }
                console.log(statusSelect);

                if(status && !statusSelect){
                    $('#submit').hide();
                    $('#loading').removeClass("hide");
                    $('#loading').addClass("show");
                    $.ajax({
                        url: "{{route('admin.saveDeposits')}}", 
                        data: {"numRef": numRef},
                        type: "POST",
                    }).done(function(result){
                        $('#payModal').modal('hide');  
                        if(result.status == 201){
                            alertify.success("Guardado Correctamente!");
                            location.reload();
                        }
                    }).fail(function(result){
                        $('#submit').show();
                        $('#loading').removeClass("show");
                        $('#loading').addClass("hide");
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    });
                }else if(status && statusSelect) {
                    $('#submit').hide();
                    $('#loading').removeClass("hide");
                    $('#loading').addClass("show");
                    $.ajax({
                        url: "{{route('admin.changeStatus')}}", 
                        data: {"selectId" : "", "status" : 3, "numRef": numRef },
                        type: "POST",
                    }).done(function(data){
                        $("#changeStatus option[value='']").attr("selected",true);
                        if(data.status == 201)
                            alertify.success('Estado ha sido cambiado correctamente');
                    
                        location.reload()
                    }).fail(function(result){
                        $('#submit').show();
                        $('#loading').removeClass("show");
                        $('#loading').addClass("hide");
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }); 
                }
                
            });
        });
    </script>