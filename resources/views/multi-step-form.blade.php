<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <style>
        .btn-bottom {
            width: 100%;
        }
    </style>
    <script src="{{ asset('js/form.js') }}"></script>
    <script src="{{ asset('js/i18n/es.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
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
                                        <img class="rounded-circle" src="{{ asset('images/perfil.png') }}" width="60px" height="60px">
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
                                <button type="button" class="remove btn btn-remove"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar</button>
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
                                    <input type="tel" name="number" id="number" class="form-control formDataShipping" placeholder="04121234567" size="11" maxlength="20" data-parsley-maxlength="20" data-parsley-pattern="^(?:(\+)58|0)(?:2(?:12|4[0-9]|5[1-9]|6[0-9]|7[0-8]|8[1-35-8]|9[1-5]|3[45789])|4(?:1[246]|2[46]))\d{7}$" required autocomplete="off" />
                                    <label class="form" for="address">DIRECCÍON:</label>
                                    <textarea class="form-control formDataShipping" name="address" id="address" row="8" placeholder="Av. Principal los dos caminos. &#10; &#10;Punto Referencia: Al frente de Farmatodo." required style="height:100px !important"></textarea>
                                    <label class="form" for="details">DETALLE ADICIONALES (OPCIONAL)</label>
                                    <textarea class="form-control formDataShipping" name="details" id="details" row="5" placeholder="Deja en la recepción" style="height:100px !important"></textarea>
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
                                @if ($coinClient ==0)
                                    <p>Seleccionar método de pago:</p>
                                @else
                                    <p>Ingresa la información de tu tarjeta de crédito o debito:</p>
                                @endif
<!--                                 <div class="row">&nbsp;</div>
                                <div class="row justify-content-center align-items-center">
                                    <label class="switch">
                                        <input type="checkbox" id="switchPay" name="switchPay">
                                        <span class="slider round"></span>
                                    </label>
                                    <label class="noPadding">Realizar pago en Efectivo</label>
                                </div> -->
                                <div class="dataPay">
                                    @if ($coinClient ==0)
                                    <div class="row checkPayment">
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

                                    <div class="row checkPayment justify-content-center align-items-center minh-10">
                                        <div class="description-payment col center">
                                            <input type="radio" class="radio-payment" name="payment" id="payment" value="PAYPAL">
                                            <img class="img-fluid" alt="Responsive image" src="{{ asset('images/paypal.png') }}">
                                            <input type="hidden" id="paymentDescription" value="PAYPAL">
                                        </div>
                                    </div>
                                    <div class="row checkPayment justify-content-center align-items-center minh-10">
                                        <div class="description-payment col center">
                                            <input type="radio" class="radio-payment" name="payment" id="payment" value="BITCOIN">
                                            <img class="img-fluid" alt="Responsive image" src="{{ asset('images/bitcoin.png') }}">    
                                            <input type="hidden" id="paymentDescription" value="BITCOIN">                                    
                                        </div>
                                    </div>
                                    @if($sales[0]->statusShipping)
                                    <div class="row checkPayment justify-content-center align-items-center minh-10">
                                        <div class="description-payment col center">
                                            <input type="radio" class="radio-payment" name="payment" id="payment" value="EFECTIVO">
                                            <img class="img-fluid" alt="Responsive image" src="{{ asset('images/dolars.png') }}">
                                            <input type="hidden" id="paymentDescription" value="EFECTIVO">
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                        <label class="form" for="nameCard">NOMBRE DE LA TARJETA:</label>
                                        <input type="text" name="nameCard" id="nameCard" class="form-control" data-parsley-minlength="3" placeholder="Joe Doe" data-parsñey-pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u" required />
                                        <label class="form" for="SelectTypeCard">SELECCONE EL TIPO DE TARJETA:</label>
                                        <div id="errorCard">
                                            <ul><li>Complete los datos de la tarjeta</li></ul>
                                        </div>
                                        <div class="row justify-content-center" >
                                            <div class="col">
                                                <label>
                                                    <input type="radio" name="typeCard" id="typeCard" value="1">
                                                    <img src="{{ asset('images/visa.png') }}" width="70px" height="60px">
                                                </label>
                                            </div>
                                            <div class="col">
                                                <label>
                                                    <input type="radio" name="typeCard" id="typeCard" value="2">
                                                    <img src="{{ asset('images/MasterCard.png') }}" width="60px" height="60px">
                                                </label>
                                            </div>
                                            <div class="col">
                                                <label>
                                                    <input type="radio" name="typeCard" id="typeCard" value="3">
                                                    <img src="{{ asset('images/americanExpress.png') }}" width="60px" height="60px">
                                                </label>
                                            </div>
                                            <div class="col">
                                                <label>
                                                    <input type="radio" name="typeCard" id="typeCard" value="33" required data-parsley-required>
                                                    <img src="{{ asset('images/diners.png') }}" width="60px" height="60px">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="form" for="numberCard">NUMERO DE LA TARJETA:</label>
                                        <input type="text" name="numberCard" id="numberCard" class="form-control" maxlength="16" placeholder="4242 4242 4242 4242" required data-parsley-maxlength="16" data-parsley-minlength="16" />
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form" for="dateCard">FECHA DE EXPIRACIÓN:</label>
                                                <div class="form-row">
                                                    <div class="col">
                                                        <input type="text" name="dateMM" id="dateMM" class="form-control" maxlength="2" placeholder="MM" data-parsley-type="integer" data-parsley-maxlength="2"/>
                                                    </div>
                                                    <label class="form">/</label>
                                                    <div class="col">
                                                        <input type="text"  name="dateYY" id="dateYY" class="form-control" maxlength="2" placeholder="YY" data-parsley-type="integer" data-parsley-maxlength="2"/>
                                                    </div>
                                                </div>
                                                <div id="statusDate"></div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form" for="cvcCard">CVV/CVC:</label>
                                                <input type="text"  name="cardCVC" id="cardCVC" class="form-control" placeholder="123" minlenght="3" maxlength="3" required data-parsley-maxlength="3" data-parsley-minlength="3"/>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>

                            <div class="form-navigation bottom">
                                <button type="button" class="next btn btn-bottom">Siguiente</button>
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
        var commerceName = "{{$commerce->name}}";
        var statusModification = {{$statusModification}};
        applicationId = "{{env('SQUARE_APP_ID')}}";

        $('#iconClose').hide();
        $('#iconDone').hide();  
        $('#iconLoading').hide();  

        $(document).ready(function(){
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
                console.log("entr");
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

            $('.save').on('click', function(){
                $.ajax({
                    url: "{{route('sale.modifysale')}}", 
                    data: {"sale_id" : _selectSale, "userUrl": "{{$userUrl}}", "codeUrl": "{{$codeUrl}}" },
                    type: "POST",
                }).done(function(result){
                    window.location=result.url;
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                });
            });
        });
    </script>
</body>
</html>