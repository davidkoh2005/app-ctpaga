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