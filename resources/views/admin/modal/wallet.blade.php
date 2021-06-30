<!--- Modal Pay-->
<div class="modal fade" id="walletModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formWallet" action="{{route('admin.settingsCryptocurrencies')}}" method="post">
                    @if($selectCryptoCurrency)
                        <input type="hidden" name='crypto' value='{{$selectCryptoCurrency->name}}'>
                    @endif

                    <input type="hidden" id='selectCryptoCurrency' value="{{$selectCryptoCurrency? 1 : 0}}">

                    <div class="table-responsive">
                        <table class="table" id="dynamic_field_wallet" style="width:99% !important;">
                            <tr>
                                <td colspan="2" style='border-color: white !important;'><button type="button" name="add" id="addDetailsWallet" class="btn btn-bottom">Agregar Detalle</button></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="row">
                                        <img class="rounded mx-auto d-block" src="{{$selectCryptoCurrency? asset('cryptocurrencies/images/'.$selectCryptoCurrency->baseAsset.'.png') : '' }}" id="showImg" width="100px">
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">Criptomoneda</label>
                                        <label class="content-select content-select-wallet">
                                            <select class="addMargin listCrypto" name="crypto" id="crypto" required>
                                                @if(!$selectCryptoCurrency)
                                                    <option value="" disabled selected>Seleccionar</option>
                                                @else
                                                    <option value="" disabled>Seleccionar</option>
                                                @endif

                                                @foreach($listCryptocurrencies as $cryptocurrency)
                                                    @if($selectCryptoCurrency && $cryptocurrency->name == $selectCryptoCurrency->name )
                                                        <option value="{{$cryptocurrency->name}}" data-url="{{ asset('cryptocurrencies/images/'.$cryptocurrency->baseAsset.'.png') }}" selected > {{$cryptocurrency->name}}</option>
                                                    @elseif(empty($cryptocurrency->address) && !$cryptocurrency->publish)
                                                        <option value="{{$cryptocurrency->name}}" data-url="{{ asset('cryptocurrencies/images/'.$cryptocurrency->baseAsset.'.png') }}"> {{$cryptocurrency->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">Dirección:</label>
                                        <div class="col">
                                            <input class="form-control" type="text" name="address" autocomplete="off" minlength="4" value="{{$selectCryptoCurrency? $selectCryptoCurrency->address : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">Publicar:</label>
                                        <div class="col">
                                        <label class="switch">
                                                @if(!empty($selectCryptoCurrency) && $selectCryptoCurrency->publish == 1)
                                                    <input type="checkbox" id="switchPublish" name="switchPublish" checked>
                                                @else
                                                    <input type="checkbox" id="switchPublish" name="switchPublish">
                                                @endif
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @if(count($detailsCryptocurrencies) > 0)
                                @foreach($detailsCryptocurrencies as $key => $detailsCryptocurrency)
                                    <input type="hidden" name="allDetailsCryptocurrency[]" value="{{$detailsCryptocurrency->id}}">
                                    <tr id="rowDetails{{intval($key)+1}}">
                                        <td>
                                            <div class="row">&nbsp;</div>
                                            <input type="hidden" name="idDetailsCryptocurrency[]" value="{{$detailsCryptocurrency->id}}">
                                            <div class="mb-3 row">
                                                <label class="col-sm-4 col-form-label">Nombre:</label>
                                                <div class="col">
                                                    <input class="form-control" type="text" name="detailsCryptocurrencyKey[]" autocomplete="off" minlength="4" value="{{$detailsCryptocurrency->key}}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-sm-4 col-form-label">Valor:</label>
                                                <div class="col">
                                                    <input class="form-control" type="text" name="detailsCryptocurrencyValue[]" autocomplete="off" minlength="4" value="{{$detailsCryptocurrency->value}}" required>
                                                </div>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button type="submit" class="submit btn btn-bottom" id="submit_wallet" form="formWallet">Guardar Billetera</button>
                    <div class="row marginAuto hide" id="loading">
                        <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loadingTransparent.gif') }}">
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




    <script>
        $(document).ready( function () {
            var idDetails = 0;

            if ($('#selectCryptoCurrency').val() == 0) {
                $('.listCrypto').prop('disabled', false);
            }
            else {
                $('.listCrypto').prop('disabled', 'disabled');
            }

            $('.listCrypto').on('change', function() {
                $("#showImg").attr("src", $(this).find(':selected').attr('data-url'));
			});

            $('#addDetailsWallet').click(function(){
                idDetails++;
                $('#dynamic_field_wallet').append('\
                    <tr id="rowDetails'+idDetails+'">\
                        <td>\
                            <div class="row">&nbsp;</div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Nombre:</label>\
                                <div class="col">\
                                    <input class="form-control" type="text" name="detailsCryptocurrencyKey[]" autocomplete="off" minlength="4" required>\
                                </div>\
                            </div>\
                            <div class="mb-3 row">\
                                <label class="col-sm-4 col-form-label">Valor:</label>\
                                <div class="col">\
                                    <input class="form-control" type="text" name="detailsCryptocurrencyValue[]" autocomplete="off" minlength="4" required>\
                                </div>\
                            </div>\
                        </td>\
                        <td style="text-align: center; vertical-align: middle; border-left-color: transparent !important; border-left-width: 2px; border-left-style: solid;">\
                            <button type="button" name="remove" id="'+idDetails+'" class="btn btn-danger btn_remove_wallet"><i class="fa fa-trash" aria-hidden="true"></i></button>\
                        </td>\
                    </tr>\
                ');
            });

            $(document).on('click', '.btn_remove_wallet', function(){
                var button_id = $(this).attr("id"); 
                $('#rowDetails'+button_id+'').remove();
            });

        });
    </script>