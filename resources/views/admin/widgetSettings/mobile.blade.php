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