<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/balance.css') }}">
    <style>
        .btn-bottom {
            width: 100%;
        }
    </style>
    <script src="{{ asset('js/form.js') }}"></script>
    <script src="{{ asset('js/i18n/es.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/stateMunicipalities.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bookshop/jquery.maskMoney.min.js') }}"></script>

    @if($coinClient==0)
    <!-- link to the SqPaymentForm library -->
    <script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform">
    </script>

    <!-- link to the local custom styles for SqPaymentForm -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bookshop/mysqpaymentform.css') }}">
    @endif
</head>
<body>
    <Section>
        <div class="container">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="row colorGrey">
                        <div class="col-12">
                            <div class="row">
                                <div class="form-navigation float-left col-md-2 col-2">
                                    <button type="button" class="previous btn">
                                        <svg width="45px" height="45px" viewBox="0 0 16 16" class="bi bi-chevron-left" fill="currentColor">
                                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="float-left col-md-7 col-6">
                                    <h5 class="form-sales" id="form-sales"> {{$commerce->name}} </h5>
                                </div>
                                <div class="col-md-2 col text-right profile">
                                    @if($picture)
                                        <img class="rounded-circle" src="{{$picture->url}}" width="60px" height="60px">
                                    @else
                                        <img class="rounded-circle" src="{{ asset('images/perfilUser.png') }}" width="60px" height="60px">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <h5 class="title-sales"> Ventas </h5>
                    <div class="row">&nbsp;</div>
                    <div class="card-body">
                        @if (Session::has('message'))
                            <div class="alert alert-danger">
                                <strong>Error: </strong> {{Session::get('message') }}
                            </div>
                        @endif
                        <form id="payment-form" class="contact-form" method='POST' action="{{route('form.formSubmit')}}">
                            @csrf
                            <div class= "form-section center">
                                <span id="coin">@if ($coinClient == 0) $ @else Bs @endif</span> <span id="total"> {{$total}}</span>
                                <h5 class="styleText"> Monto Total </h5>
                                <div class="row">&nbsp;</div>
                                @foreach ($sales as $sale)
                                    <div class="row sales justify-content-center align-items-center minh-10" id="listSale">
                                        <div class="float-left">
                                            @if($sale->image != "")    
                                                <img src="{{$sale->image}}" width="100px" height="80px">
                                            @else
                                                <img src="{{ asset('images/adicionales.png') }}" width="100px" height="80px">
                                            @endif
                                        </div>
                                        <div class="quantity" id="desingQuantity">{{$sale->quantity}}</div>
                                        <div class="verticalLine"></div>
                                        <input type="hidden" name="idSale" id="idSale" value="{{$sale->id}}">
                                        <div class="name"><label>{{$sale->name}}</label><br> <script> document.write(showPrice("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}))</script></div>
                                        <div class="verticalLine"></div>
                                        <div class="total"><script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
                                    </div>
                                    <div class="row sales justify-content-center align-items-center minh-10">
                                        @if ($coinClient == 1)
                                            <div class="total bold d-block d-sm-none"> Total:</div>
                                            <div class="total d-block d-sm-none"> <script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
                                        @endif
                                    </div>
                                @endforeach

                                <input type="hidden" id="nameClient"  name="nameClient" value="{{$nameClient}}">
                                <input type="hidden" id="coinClient"  name="coinClient" value="{{$coinClient}}">
                                <input type="hidden" id="userUrl"  name="userUrl" value="{{$userUrl}}">
                                <input type="hidden" id="codeUrl"  name="codeUrl" value="{{$codeUrl}}">
                            </div>
                            <div class= "form-section center">
                                <div id="saleQuantity"></div>
                                <button type="button" class="remove btn btn-remove"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar Producto</button>
                                <div class="numPad">
                                    <div class="row">
                                        <div class="col" onclick="addNum(1)">1</div>
                                        <div class="col" onclick="addNum(2)">2</div>
                                        <div class="col" onclick="addNum(3)">3</div>
                                    </div>
                                    <div class="row">
                                        <div class="col" onclick="addNum(4)">4</div>
                                        <div class="col" onclick="addNum(5)">5</div>
                                        <div class="col" onclick="addNum(6)">6</div>
                                    </div>
                                    <div class="row">
                                        <div class="col" onclick="addNum(7)">7</div>
                                        <div class="col" onclick="addNum(8)">8</div>
                                        <div class="col" onclick="addNum(9)">9</div>
                                    </div>
                                    <div class="row">
                                        <div class="col"> </div>
                                        <div class="col" onclick="addNum(0)">0</div>
                                        <div class="col" onclick="removeNum()"><img src="{{ asset('images/delete.png') }}" class="img-fluid" width="30px" height="30px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class= "form-section">
                                <p>Ingrese un correo electrónico donde podamos enviarte el recibo de pago:</p>
                                <label class="form" for="email">CORREO ELECTRÓNICO</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="joedoe@gmail.com" required />
                            </div>

                            <div class="form-section">
                                @if ($sales[0]->statusShipping && count($shippings)!=0)
                                <input type="hidden" id="statusShipping" value="true">
                                    <p> Seleccione un envio:</p>
                                    @foreach ($shippings as $shipping)
                                        <div class="row shippings justify-content-center align-items-center minh-10 listShipping">
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-4" id="iconChecked">
                                                <input type="radio" class="radio-shippings" name="shippings" id="shippings" value="{{$shipping->id}}">
                                                <input type="hidden" id="shippingPrice" value="{{$shipping->price}}">
                                                <input type="hidden" id="shippingCoin"  name="shippingCoin" value="{{$shipping->coin}}">
                                            </div>
                                            <div class="description-shippings col">{{$shipping->description}}</div>
                                            <input type="hidden" id="shippingDescription" value="{{$shipping->description}}">
                                            @if ($coinClient == 0) 
                                                <div class="shipping-price col"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                            @else
                                                <div class="shipping-price col-md col-12"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                            @endif
    
                                        </div>
                                    @endforeach
                                @else
                                    <input type="hidden" id="statusShipping" value="false">
                                @endif
                            </div>

                            <div class="form-section">
                                @if ($sales[0]->statusShipping && count($shippings)!=0)
                                    <p> Ingresa la dirección de envío:</p>
                                    <label class="form" for="name">NOMBRE:</label>
                                    <input type="text" name="name" id="name" class="form-control formDataShipping" data-parsley-minlength="3" placeholder="Joe Doe" data-parsñey-pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u" required />
                                    <label class="form" for="number">NUMERO DE CELULAR:</label>
                                    <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="number" id="number" class="form-control formDataShipping" placeholder="04125555555" size="11" maxlength="11" data-parsley-maxlength="11" data-parsley-pattern="^(0414|0424|0412|0416|0426)[0-9]{7}$" required autocomplete="off" />
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label class="form" for="address">Estado:</label> <br>
                                            <label class="content-select">
                                                <select class="addMargin" name="selectState" id="selectState" required="" data-parsley-required-message="Debe Seleccionar un Estado" >
                                                    <option value="" selected>Seleccionar</option>
                                                </select>
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form" for="address">Municipio:</label> <br>
                                            <label class="content-select">
                                                <select class="addMargin" name="selectMunicipalities" id="selectMunicipalities" disabled required="" data-parsley-required-message="Debe Seleccionar un Municipio">
                                                    <option value="" selected>Seleccionar</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <label class="form" for="address">DIRECCÍON:</label>
                                    <textarea class="form-control formDataShipping" name="address" id="address" row="8" placeholder="Av. Principal los dos caminos. &#10; &#10;Punto Referencia: Al frente de Farmatodo." required style="height:100px !important"></textarea>
                                    <label class="form" for="details">DETALLE ADICIONALES (OPCIONAL)</label>
                                    <textarea class="form-control" name="details" id="details" row="5" placeholder="Deja en la recepción" style="height:100px !important"></textarea>
                                @endif
                            </div>

                            <div class="form-section">
                                <p>Ingresa código de descuento:</p>
                                <label class="form" for="discount">CODE DE DESCUENTO:</label>
                                <div class="row">
                                    <div class="col-10">
                                        <input type="text" name="discount" id="discount" class="form-control" data-parsley-minlength="3" placeholder="DESCO20" autocomplete="off" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled/>
                                    </div>
                                    <div class="col">
                                        <i class="fa fa-times" aria-hidden="true"id="iconClose" style="font-size:35px; color:red"></i>
                                        <i class="fa fa-check" aria-hidden="true" id="iconDone" style="font-size:35px; color:#00cc5f"></i>
                                        <img widht="40px" height="40px" id="iconLoading" src="{{ asset('/images/loadingTransparent.gif') }}">
                                    </div>                                
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row justify-content-center align-items-center">
                                    <label class="switch">
                                        <input type="checkbox" id="switchDiscount" name="switchDiscount">
                                        <span class="slider round"></span>
                                    </label>
                                    <label class="noPadding">Tengo descuento</label>
                                </div>
                            </div> 

                            <div class="form-section">
                                <p>Revisa y paga:</p>
                                <div class="row sales justify-content-center align-items-center minh-10 addPadding">
                                    <div class="col-md-2 col-sm-2 col-3">
                                        <svg width="30px" height="30px" viewBox="0 0 16 16" class="bi bi-cart2" fill="currentColor">
                                            <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                                        </svg>
                                    </diV>
                                    <div class="name col">Pedido <br>{{$quantity}} {{$msg}}</div>
                                    <input type="hidden" id="orderClient" name="orderClient" value="{{$quantity}} {{$msg}}">
                                    @if ($coinClient == 0) 
                                        <div class="total col">$ {{$total}}</div>
                                    @else
                                        <div class="total col-md col-12">Bs {{$total}}</div>
                                    @endif

                                    <input type="hidden" id="totalProductService" name="totalProductService" value="{{$total}}">
                                    <input type="hidden" id="percentageSelect" name="percentageSelect" value="0">
                                </div>
                                <div class="row sales justify-content-center align-items-center minh-10 addPadding">
                                    <div class="col-md-2 col-sm-2 col-3">
                                        <img src="{{ asset('images/envios.png') }}" class="figure-img img-fluid rounded" width="50px" height="50px">
                                    </diV>
                                    <div class="name col">Envio</div>
                                    
                                    @if ($coinClient == 0) 
                                        <div class="col total showShipping" id="showShipping"></div>
                                    @else
                                        <div class="col-md col-12 total showShipping" id="showShipping"></div>
                                    @endif
                                </div>
                                <div class="showPercentage"></div>
                                <div class="totalGlobal" id="totalGlobal"></div>
                                <input type="hidden" id="selectShipping" name="selectShipping">
                                <input type="hidden" id="priceShipping" name="priceShipping" >
                                <input type="hidden" id="totalAll" name="totalAll">
                            </div> 

                            <div class= "form-section">
                                <p>Seleccionar método de pago:</p>
                                <div class="dataPay">
                                    @if ($coinClient ==0)
                                        <div class="row checkPayment checkPaymentWhite">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="CARD">
                                                <img class="img-fluid " alt="Responsive image" src="{{ asset('images/square.png') }}">
                                            </div>
                                            
                                            <input type="hidden" id="paymentDescription" value="CARD">
                                        </div>

                                        <div id="errorCard">
                                            <ul><li>Complete los datos de la tarjeta</li></ul>
                                        </div>

                                        <div id="showCardForm" style="padding-bottom: 80px; margin-top: 100px;">
                                            <div id="form-container">
                                                <div id="sq-card-number"></div>
                                                <div class="third" id="sq-expiration-date"></div>
                                                <div class="third" id="sq-cvv"></div>
                                                <div class="third" id="sq-postal-code"></div>
                                            </div> 
                                            <input type="hidden" name="nonce" id="nonce">
                                            <input type="hidden" name="idempotency_key" id="idempotency_key">
                                        </div>
                                        @if($zelle != NULL)
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="ZELLE">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/zelle.png') }}">
                                                <input type="hidden" id="paymentDescription" value="ZELLE">
                                            </div>
                                        </div>
                                        <div id="showZelle" style="padding-bottom: 80px; display:none;">
                                            <label>Por favor envíe su pago Zelle al siguiente correo: <strong>{{$zelle->value}}</strong> <br> Cuando reciba el código de confirmación del envío, ingréselo en el siguiente campo.</label>
                                            <div class="justify-content-center align-items-center minh-10">
                                                <label class="col col-form-label">Nombre de Cuenta Zelle:</label>
                                                <div class="col">
                                                    <input type="text" class="form-control nameZelle" name="nameZelle" id="nameZelle" data-parsley-minlength="3" minlength="3" placeholder="Joe Doe" data-parsñey-pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="justify-content-center align-items-center minh-10">
                                                <label class="col col-form-label">Zelle ID Confirmación:</label>
                                                <div class="col">
                                                <input type="text" class="form-control idConfirmZelle" name="idConfirmZelle" id="idConfirmZelle" onkeyup="mayus(this);" data-parsley-minlength="12" minlength="12" maxlenght="12" data-parsñey-pattern="[A-Z0-9]{11,12}" autocomplete="off"/>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="PAYPAL">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/paypal.png') }}">
                                                <input type="hidden" id="paymentDescription" value="PAYPAL">
                                            </div>
                                        </div>
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="BITCOIN">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/bitcoin.png') }}">    
                                                <input type="hidden" id="paymentDescription" value="BITCOIN">                                    
                                            </div>
                                        </div>
                                        @if($sales[0]->statusShipping)
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="EFECTIVO">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/dolars.png') }}">
                                                <input type="hidden" id="paymentDescription" value="EFECTIVO">
                                            </div>
                                        </div>
                                        @endif
                                    @else
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="TRANSFERENCIA">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/transferencia.png') }}">
                                                <input type="hidden" id="paymentDescription" value="TRANSFERENCIA">
                                            </div>
                                        </div>
                                        <div class="errorBs errorTransfers">
                                            <ul><li class="errorMissing">El Total pagado debe ser igual a monto a pagar!</li></ul>
                                        </div>
                                        <div id="showTransfers" style="padding-bottom: 80px; display:none;">
                                        
                                            <table class="table table-bordered" id="dynamic_field_transfers" bordercolor="#ff0000"></table>
                                            
                                            <div style="text-align: end;">
                                                <label><strong>Cantidad de Transferencia: <span id="showCountTransfers">1</span> </strong></label> <br>
                                                <label><strong>Monto a pagar: <span class="totalPayment">Bs 0</span></strong></label> <br>
                                                <label style="color:red !important;"><strong>Monto Restante: <span class="showRemainingAmount"></span> </strong></label> <br>
                                                <label><strong>Total Pagado: <span class="showTotalPaid">Bs 0</span></strong></label> <br>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <label style="margin-top: 12px;">Agregar nuevo Transferencia</label><button type="button" name="add" id="addTransfers" class="btn" data-toggle="tooltip" data-placement="top" title="Agregar otra transferencia"><i class="fa fa-plus-circle button-add" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="row checkPayment checkPaymentWhite justify-content-center align-items-center minh-10">
                                            <div class="description-payment col center">
                                                <input type="radio" class="radio-payment" name="payment" id="payment" value="PAGO MOVIL">
                                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/pagoMovil.png') }}">
                                                <input type="hidden" id="paymentDescription" value="PAGO MOVIL">
                                            </div>
                                        </div>
                                        <div class="errorBs errorMobilePayments">
                                            <ul><li class="errorMissing">El Total pagado debe ser igual a monto a pagar!</li></ul>
                                        </div>
                                        <div id="showMobilePayment" style="padding-bottom: 80px; display:none;">
                                            
                                            <table class="table table-bordered" id="dynamic_field_mobile" bordercolor="#ff0000"></table>
                                            
                                            <div style="text-align: end;">
                                                <label><strong>Cantidad de Pago Móvil: <span id="showCountMobiles">1</span> </strong></label> <br>
                                                <label><strong>Monto a pagar: <span class="totalPayment">Bs 0</span></strong></label> <br>
                                                <label style="color:red !important;"><strong>Monto Restante: <span class="showRemainingAmount"></span> </strong></label> <br>
                                                <label><strong>Total Pagado: <span class="showTotalPaid">Bs 0</span></strong></label> <br>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <label style="margin-top: 12px;">Agregar nuevo Pago Móvil</label> <button type="button" name="add" id="addMobiles" class="btn" data-toggle="tooltip" data-placement="top" title="Agregar otro pago móvil"><i class="fa fa-plus-circle button-add" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>

                            <div class="form-navigation bottom">
                                <button type="button" class="next btn btn-bottom" role="button" tabIndex="0">Siguiente</button>
                                <button type="button" class="pay btn btn-bottom btn-active">Pagar</button>
                                <button type="button" class="save btn btn-bottom btn-active">Guardar</button>
                                <button type="submit" class="submit btn btn-bottom btn-active">Realizar Pago</button>
                            </div>
                            <div class="row justify-content-center"id="loading">
                                <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('/images/loadingTransparent.gif') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Section>

    <script> 
        var urlLoading = "{{ asset('/images/loadingTransparent.gif') }}";
        var commerceName = "{{$commerce->name}}";
        var statusModification = {{$statusModification}};
        applicationId = "{{env('SQUARE_APP_ID')}}";
        
        var MUNICIPALITIES, STATE, arrayMunicipalities, arrayState, dataTransfer, dataMobilePayment;
        var iTransfers =1;
        var iMobiles = 1;
        var totalPaid = 0;

        $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
        $('#iconClose').hide();
        $('#iconDone').hide();  
        $('#iconLoading').hide();  

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


        function showState(item, index) {
            if(item == 'Distrito Capital' || item == 'Miranda')
                $('#selectState').append('<option value="'+item+'">'+item+'</option>');
        }

        function showMunicipalities(listMunicipalities) {
            $.each(listMunicipalities, function (key, value) {
                $('#selectMunicipalities').append('<option value="'+value+'">'+value+'</option>');
            });
        }

        $(document).ready(function(){           
            $('#selectState').on('change', function() {
                $('#selectMunicipalities').prop('disabled', 'disabled');
                $('#selectMunicipalities option').remove();
                $('#selectMunicipalities').append('<option value="" selected>Seleccionar</option>');
                if(this.value != '')
                    $.ajax({
                        url: "{{route('show.municipalities')}}", 
                        data: {"states" : this.value},
                        type: "POST",
                    }).done(function(result){
                        if(result.statusCode == 201){
                            arrayMunicipalities=result.data;
                            arrayMunicipalities.forEach(showMunicipalities);
                            $('#selectMunicipalities').prop('disabled', false);
                        }

                    }).fail(function(result){
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    });
            });

            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                    callback.apply(context, args);
                    }, ms || 0);
                };
            }

            $('#discount').keyup(delay(function (e) {
                $('#iconClose').hide();
                $('#iconDone').hide();  
                $('#iconLoading').show(); 
                $.ajax({
                    url: "{{ url('/verify')}}", 
                    data: {"input" : $(this).val(), "user_id" : "{{$commerce->user_id}}"},
                    type: "POST",
                    dataType: "json",
                }).done(function(result){
                    $('#percentageSelect').val(result['percentage']);
                    $('#iconClose').hide();
                    $('#iconDone').show();  
                    $('#iconLoading').hide(); 
                    $('.next').show();       
                }).fail(function(result){  
                    $('#percentageSelect').val(0);
                    $('#iconClose').show();
                    $('#iconDone').hide();  
                    $('#iconLoading').hide(); 
                    $('.next').hide();                    
                });
            }, 500));
            
            $('.save').on('click', function(){
                if(parseInt($('#saleQuantity').text()) == 0){
                    alertify.error('Debe ser diferente a cero');
                }else{
                    $.ajax({
                        url: "{{route('sale.modifysale')}}", 
                        data: {"sale_id" : _selectSale, "quantity" : $('#saleQuantity').text(), "userUrl": "{{$userUrl}}", "codeUrl": "{{$codeUrl}}" },
                        type: "POST",
                    }).done(function(result){
                        window.location=result.url;
                    }).fail(function(result){
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    });
                }
            });

            $('.remove').on('click', function(){
                $.ajax({
                    url: "{{route('sale.removeSale')}}", 
                    data: {"sale_id" : _selectSale, "userUrl": "{{$userUrl}}", "codeUrl": "{{$codeUrl}}" },
                    type: "POST",
                }).done(function(result){
                    window.location=result.url;
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                }); 
            });

            $(document).on('change', '.selectBankTransfers', function() {
                var $row = jQuery(this).closest('tr');
                var $idTransfers = $row[0]['id'];

                if($('#'+$idTransfers).find(".showDataTransfers").css('display') == 'block')
                    $('#'+$idTransfers).find(".showDataTransfers").css({"display":"none"});

                $('#'+$idTransfers).find(".loading").css({"display":"block"});

                if($(this).val() != "" && _coinClient == 1){
                    $.ajax({
                        url: "{{route('settingsBank.showData')}}", 
                        data: {"selectBank" : $(this).val(), "type" : 0,},
                        type: "POST",
                    }).done(function(result){
                        dataTransfer = result.data;
                        $('#'+$idTransfers).find(".loading").css({"display":"none"});
                        $('#'+$idTransfers).find(".showDataTransfers").css({"display":"block"});
                        $('#'+$idTransfers).find(".showAccountName").text(dataTransfer.accountName);
                        $('#'+$idTransfers).find(".showIdCard").text(dataTransfer.idCard);
                        $('#'+$idTransfers).find(".showAccountNumber").text(dataTransfer.accountNumber);
                        
                        if(dataTransfer.accountType == 'A')
                            $('#'+$idTransfers).find(".showAccountType").text('Ahorro'); 
                        else
                            $('#'+$idTransfers).find(".showAccountType").text('Corriente'); 

                        $('#'+$idTransfers).find(".datepicker").attr('disabled', false);
                        $('#'+$idTransfers).find(".amount").attr('disabled', false);
                        $('#'+$idTransfers).find(".numTransfers").attr('disabled', false);
                    }).fail(function(result){
                        $('#'+$idTransfers).find(".selectBankTransfers option[value='']").prop("selected",true);
                        $('#'+$idTransfers).find(".loading").css({"display":"none"});
                        $('#'+$idTransfers).find(".showDataTransfers").css({"display":"none"});
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }); 
                }
            });

            $(document).on('change', '.selectBankMobiles', function() {
                var $row = jQuery(this).closest('tr');
                var $idMobiles = $row[0]['id'];

                if($('#'+$idMobiles).find(".showDataMobiles").css('display') == 'block')
                    $('#'+$idMobiles).find(".showDataMobiles").css({"display":"none"});

                $('#'+$idMobiles).find(".loading").css({"display":"block"});

                if($(this).val() != "" && _coinClient == 1){
                    $.ajax({
                        url: "{{route('settingsBank.showData')}}", 
                        data: {"selectBank" : $(this).val(), "type" : 1,},
                        type: "POST",
                    }).done(function(result){
                        dataMobiles = result.data;
                        $('#'+$idMobiles).find(".loading").css({"display":"none"});
                        $('#'+$idMobiles).find(".showDataMobiles").css({"display":"block"});
                        $('#'+$idMobiles).find(".showIdCard").text(dataMobiles.idCard);
                        $('#'+$idMobiles).find(".showPhone").text(dataMobiles.phone);

                        $('#'+$idMobiles).find(".datepicker").attr('disabled', false);
                        $('#'+$idMobiles).find(".amount").attr('disabled', false);
                        $('#'+$idMobiles).find(".numTransfers").attr('disabled', false);

                    }).fail(function(result){
                        $('#'+$idMobiles).find(".selectBankMobiles option[value='']").prop("selected",true);
                        $('#'+$idMobiles).find(".loading").css({"display":"none"});
                        $('#'+$idMobiles).find(".showDataMobiles").css({"display":"none"});
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    }); 
                }
            });

            $('#addTransfers').click(function(){

                iTransfers++;
                $('#dynamic_field_transfers').append('\
                    <tr id="rowTransfers'+iTransfers+'" class="trTransfers">\
                        <td>\
                            <div class="d-flex justify-content-end">\
                                <button type="button" name="remove" id="'+iTransfers+'" class="btn btn_remove" data-toggle="tooltip" data-placement="top" title="Eliminar transferencia" data-type="0"><i class="fa fa-minus-circle button-remove" aria-hidden="true"></i></button>\
                            </div>\
                            <div class="mx-auto">\
                                <label><strong>Transferencia No. '+iTransfers+'</strong> </label> <br>\
                            </div>\
                            <div class="showDataTransfers" style="text-align: initial; display:none;">\
                                <label><strong>Titular: </strong> <span class="showAccountName"></span></label> <br>\
                                <label><strong>Cédula o Rif: </strong> <span class="showIdCard"></span></label> <br>\
                                <label><strong>Número de Cuenta: </strong> <span class="showAccountNumber"></span></label> <br>\
                                <label><strong>Tipo de Cuenta: </strong> <span class="showAccountType"></span></label> <br>\
                            </div>\
                            <div class="row justify-content-center loading" id="loading" style="display:none;">\
                                <img widht="80px" height="80px" class="justify-content-center" src="'+urlLoading+'">\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Banco:</label>\
                                <label class="content-select content-select-bank">\
                                    <select class="addMargin selectBankTransfers" name="bank[]" id="bank" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        @foreach($transfers as $transfer)\
                                            <option value="{{$transfer->bank}}">{{$transfer->bank}}</option>\
                                        @endforeach\
                                    </select>\
                                </label>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Fecha:</label>\
                                <div class="col">\
                                    <input type="text" class="form-control datepicker" name="date[]" autocomplete="off" disabled readonly required/>\
                                </div>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Monto:</label>\
                                <div class="col">\
                                    <input type="text" class="form-control amount" name="amount[]" id="amount" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                                </div>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Número de transferencia:</label>\
                                <div class="col">\
                                    <input type="number" class="form-control numTransfers" name="numTransfers[]" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                                </div>\
                            </div>\
                            <div class="row">&nbsp;</div>\
                        </td>\
                    </tr>\
                ');
                $("#showCountTransfers").text(iTransfers);
                $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
                addDateTime();
            });

            $('#addMobiles').click(function(){
                iMobiles++;
                $('#dynamic_field_mobile').append('\
                    <tr id="rowMobiles'+iMobiles+'" class="trMobile">\
                        <td>\
                            <div class="d-flex justify-content-end">\
                                <button type="button" name="remove" id="'+iMobiles+'" class="btn btn_remove" data-toggle="tooltip" data-placement="top" title="Eliminar transferencia" data-type="0"><i class="fa fa-minus-circle button-remove" aria-hidden="true"></i></button>\
                            </div>\
                            <div class="mx-auto">\
                                <label><strong>Pago Móvil No. '+iMobiles+'</strong> </label> <br>\
                            </div>\
                            <div class="showDataMobiles" style="text-align: initial; display:none;">\
                                <label><strong>Cédula o Rif: </strong> <span class="showIdCard"></span></label> <br>\
                                <label><strong>Teléfono: </strong> <span class="showPhone"></span></label> <br>\
                            </div>\
                            <div class="row justify-content-center loading" id="loading" style="display:none;">\
                                <img widht="80px" height="80px" class="justify-content-center" src="'+urlLoading+'">\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Banco:</label>\
                                <label class="content-select content-select-bank">\
                                    <select class="addMargin selectBankMobiles" name="bank[]" id="bank" required>\
                                        <option value="" disabled selected>Seleccionar</option>\
                                        @foreach($mobilePayments as $mobilePayment)\
                                            <option value="{{$mobilePayment->bank}}">{{$mobilePayment->bank}}</option>\
                                        @endforeach\
                                    </select>\
                                </label>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Fecha:</label>\
                                <div class="col">\
                                    <input type="text" class="form-control datepicker" name="date[]" autocomplete="off" disabled readonly required/>\
                                </div>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Monto:</label>\
                                <div class="col">\
                                    <input type="text" class="form-control amount" name="amount[]" id="amount" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                                </div>\
                            </div>\
                            <div class="justify-content-center align-items-center minh-10">\
                                <label class="col col-form-label">Número de transacción:</label>\
                                <div class="col">\
                                    <input type="number" class="form-control numTransfers" name="numTransfers[]" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                                </div>\
                            </div>\
                            <div class="row">&nbsp;</div>\
                        </td>\
                    </tr>\
                ');
                $("#showCountMobiles").text(iMobiles);
                $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
                addDateTime();
            });

            $(document).on('click', '.btn_remove', function(){
                var type = $(this).data("type");
                var button_id = $(this).attr("id"); 

                if(type == 0){
                    iTransfers--;
                    $('#rowTransfers'+button_id+'').remove();
                    $("#showCountTransfers").text(iTransfers);
                }
                else{
                    iMobiles--;
                    $('#rowMobiles'+button_id+'').remove();
                    $("#showCountMobiles").text(iMobiles);
                }
            });

            $(document).on('blur', '.amount', function() {
                totalPaid = 0;
                $('input[name^="amount"]').each(function() {
                    var $row = jQuery(this).closest('tr');
                    var $idRow = $row[0]['id'];

                    var amount = $('#'+$idRow).find(".amount").val();
                    amount = amount.replace("Bs ", "");
                    amount = amount.replace(/\./g, "");
                    amount = amount.replace(/,/g, ".");
                    amount = parseFloat(amount);

                    totalPaid += amount;
                });

                $(".showRemainingAmount").text("Bs "+formatter.format(totalPayment - totalPaid));
                $(".showTotalPaid").text("Bs "+formatter.format(totalPaid));
            });
        });

        function addFormTransfers(){
            iTransfers++;
            $('#dynamic_field_transfers').append('\
                <tr id="rowTransfers'+iTransfers+'" class="trTransfers">\
                    <td>\
                        <div class="mx-auto">\
                            <label><strong>Transferencia</strong> </label> <br>\
                        </div>\
                        <div class="showDataTransfers" style="text-align: initial; display:none;">\
                            <label><strong>Titular: </strong> <span class="showAccountName"></span></label> <br>\
                            <label><strong>Cédula o Rif: </strong> <span class="showIdCard"></span></label> <br>\
                            <label><strong>Número de Cuenta: </strong> <span class="showAccountNumber"></span></label> <br>\
                            <label><strong>Tipo de Cuenta: </strong> <span class="showAccountType"></span></label> <br>\
                        </div>\
                        <div class="row justify-content-center loading" id="loading" style="display:none;">\
                            <img widht="80px" height="80px" class="justify-content-center" src="'+urlLoading+'">\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Banco:</label>\
                            <label class="content-select content-select-bank">\
                                <select class="addMargin selectBankTransfers" name="bank[]" id="bank" required>\
                                    <option value="" disabled selected>Seleccionar</option>\
                                    @foreach($transfers as $transfer)\
                                        <option value="{{$transfer->bank}}">{{$transfer->bank}}</option>\
                                    @endforeach\
                                </select>\
                            </label>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Fecha:</label>\
                            <div class="col">\
                                <input type="text" class="form-control datepicker" name="date[]" autocomplete="off" disabled readonly required/>\
                            </div>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Monto:</label>\
                            <div class="col">\
                                <input type="text" class="form-control amount" name="amount[]" id="amount" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                            </div>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Número de transferencia:</label>\
                            <div class="col">\
                                <input type="number" class="form-control numTransfers" name="numTransfers[]" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                            </div>\
                        </div>\
                        <div class="row">&nbsp;</div>\
                    </td>\
                </tr>\
            ');
            $("#showCountTransfers").text(iTransfers);
            $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
        }

        function addFormMobiles(){
            iMobiles++;
            $('#dynamic_field_mobile').append('\
                <tr id="rowMobiles'+iMobiles+'" class="trMobile">\
                    <td>\
                        <div class="mx-auto">\
                            <label><strong>Pago Móvil</strong> </label> <br>\
                        </div>\
                        <div class="showDataMobiles" style="text-align: initial; display:none;">\
                            <label><strong>Cédula o Rif: </strong> <span class="showIdCard"></span></label> <br>\
                            <label><strong>Teléfono: </strong> <span class="showPhone"></span></label> <br>\
                        </div>\
                        <div class="row justify-content-center loading" id="loading" style="display:none;">\
                            <img widht="80px" height="80px" class="justify-content-center" src="'+urlLoading+'">\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Banco:</label>\
                            <label class="content-select content-select-bank">\
                                <select class="addMargin selectBankMobiles" name="bank[]" id="bank" required>\
                                    <option value="" disabled selected>Seleccionar</option>\
                                    @foreach($mobilePayments as $mobilePayment)\
                                        <option value="{{$mobilePayment->bank}}">{{$mobilePayment->bank}}</option>\
                                    @endforeach\
                                </select>\
                            </label>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Fecha:</label>\
                            <div class="col">\
                                <input type="text" class="form-control datepicker" name="date[]" autocomplete="off" disabled readonly required/>\
                            </div>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Monto:</label>\
                            <div class="col">\
                                <input type="text" class="form-control amount" name="amount[]" id="amount" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                            </div>\
                        </div>\
                        <div class="justify-content-center align-items-center minh-10">\
                            <label class="col col-form-label">Número de transacción:</label>\
                            <div class="col">\
                                <input type="number" class="form-control numTransfers" name="numTransfers[]" data-parsley-minlength="3" minlength="3" autocomplete="off" disabled required/>\
                            </div>\
                        </div>\
                        <div class="row">&nbsp;</div>\
                    </td>\
                </tr>\
            ');
            $("#showCountMobiles").text(iMobiles);
            $(".amount").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix:'Bs '});
        }

        function addDateTime(){
            var date = new Date();
            date.setDate(date.getDate() - 1);
            
            $('.datepicker').datepicker({
                orientation: "bottom auto",
                startDate: date,
                endDate: new Date(),
                language: "es",
                autoclose: true,
                todayHighlight: true
            });
        }
    </script>
</body>
</html>