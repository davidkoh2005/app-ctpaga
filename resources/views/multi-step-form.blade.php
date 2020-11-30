<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../css/styleForm.css">
    <script src="../js/form.js"></script>
    <script src="../js/i18n/es.js"></script>
<body>
    <Section>
        <div class="container">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-left">
                                <div class="col form-navigation">
                                    <button type="button" class="previous btn">
                                        <svg width="30px" height="30px" viewBox="0 0 16 16" class="bi bi-chevron-left" fill="currentColor">
                                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="col-md-auto col-sm-auto col-auto">
                                    <h5 class="form-sales"> Ventas </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" class="contact-form">
                                @csrf
                                <div class= "form-section center">
                                    <img class="rounded-circle" src="{{$picture->url}}" width="100px" height="100px">
                                    <h3> {{$commerce->name}}</h3>
                                    <div class="row">&nbsp;</div>
                                    <span id="coin">@if ($coinClient == 0) $ @else Bs @endif</span> <span id="total"> {{$total}}</span>
                                    <div class="row">&nbsp;</div>
                                    <h3> Por </h3>
                                    <div class="row">&nbsp;</div>
                                    @foreach ($sales as $sale)
                                        <div class="row sales justify-content-center align-items-center minh-10">
                                            <div class="quantity col-md-2 col-sm-2 col-3"><div id="desingQuantity">{{$sale->quantity}}</div></div>
                                            <div class="name col">{{$sale->name}}<br> <script> document.write(showPrice("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}))</script></div>
                                            <div class="total col"><script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class= "form-section">
                                    <p>Ingrese un correo electrónico donde podamos enviarte el recibo de pago:</p>
                                    <label for="email">CORREO ELECTRÓNICO</label>
                                    <input type="email" name="email" class="form-control" placeholder="joedoe@gmail.com" required />
                                </div>

                                @if ($sales[0]->statusShipping && count($shippings)!=0)
                                <script> statusShipping = true;</script>
                                <div class="form-section">
                                    @foreach ($shippings as $shipping)
                                        <div class="row shippings">
                                            <div class="col-md-2 col-sm-2 col-3" id="iconChecked">
                                                <input type="radio" class="radio-shippings" name="shippings" id="shippings">
                                                <input type="hidden" id="shippingPrice" value="{{$shipping->price}}">
                                                <input type="hidden" id="shippingCoin" value="{{$shipping->coin}}">
                                            </div>
                                            <div class="description-shippings col">{{$shipping->description}}</div>
                                            <div class="shipping-price col"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                @if ($sales[0]->statusShipping && count($shippings)!=0)
                                <div class="form-section">
                                    <p> Ingresa la dirección de envío:</p>
                                    <label for="name">NOMBRE:</label>
                                    <input type="text" name="name" class="form-control" data-parsley-minlength="3" placeholder="Joe Doe" data-parsñey-pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u" required />
                                    <label for="number">NUMERO DE CELULAR:</label>
                                    <input type="tel" name="number" class="form-control" placeholder="04121234567" maxlength="20" data-parsley-maxlength="20" data-parsley-pattern="^(?:(\+)58|0)(?:2(?:12|4[0-9]|5[1-9]|6[0-9]|7[0-8]|8[1-35-8]|9[1-5]|3[45789])|4(?:1[246]|2[46]))\d{7}$" required />
                                    <label for="address">DIRECCÍON:</label>
                                    <textarea class="form-control" name="address" row="3" placeholder="Av. Principal los dos caminos" required></textarea>
                                    <label for="details">DETALLE ADICIONALES (OPCIONAL)</label>
                                    <textarea class="form-control" name="details" row="3" placeholder="Deja en la recepción"></textarea>
                                </div>
                                @endif

                                <div class= "form-section">
                                    <p>Ingresa la información de tu tarjeta de crédito o debito (Visa o Master Card):</p>
                                    <div class="row center">
                                        <div class="col">
                                            <img src="../images/visa.png" class="img-fluid" width="150px" height="150px">
                                        </div>
                                        <div class="col">
                                        <img src="../images/MasterCard.png" class="img-fluid" width="100px" height="100px">
                                        </div>
                                    </div>
                                    <label for="nameCard">NOMBRE DE LA TARJETA:</label>
                                    <input type="text" name="nameCard" class="form-control" data-parsley-minlength="3" placeholder="Joe Doe" data-parsñey-pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u" required />
                                    <label for="numberCard">NUMERO DE LA TARJETA:</label>
                                    <input type="number" name="numberCard" id="numberCard" class="form-control" data-parsley-maxlength="16" placeholder="4012888888881881" data-parsley-pattern="^(?:4\d([\- ])?\d{6}\1\d{5}|(?:4\d{3}|5[1-5]\d{2}|6011)([\- ])?\d{4}\2\d{4}\2\d{4})$" required />
                                    <div class="row">
                                        <div class="col">
                                            <label for="dateCard">FECHA DE EXPIRACIÓN:</label>
                                            <input type="text" name="dateCard" id="dateCard" class="form-control" placeholder="MM/AA" maxlength="5" data-parsley-pattern="^(0[1-9]|1[0-2])\/(2[0-9]{1})$" required />
                                        </div>
                                        <div class="col">
                                            <label for="cvcCard">CVV/CVC:</label>
                                            <input type="number" name="cvcCard" class="form-control" minlength="3" maxlength="3" data-parsley-maxlength="3" placeholder="123" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <p>Ingresa código de descuento:</p>
                                    <label for="discount">CODE DE DESCUENTO:</label>
                                    <input type="text" name="discount" id="discount" class="form-control" data-parsley-minlength="3" placeholder="DESCO20" onkeyup="javascript:this.value=this.value.toUpperCase();" />
                                    <div class="row">&nbsp;</div>
                                    <div class="row justify-content-center align-items-center">
                                        <label class="switch">
                                            <input type="checkbox" id="switchDiscount" name="switchDiscount">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="noPadding">No tengo descuento</label>
                                    </div>
                                </div> 

                                <div class="form-section">
                                    <p>Revisa y paga:</p>
                                    <div class="row sales justify-content-center align-items-center minh-10">
                                        <div class="col-md-2 col-sm-2 col-3">
                                            <svg width="30px" height="30px" viewBox="0 0 16 16" class="bi bi-cart2" fill="currentColor">
                                                <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                                            </svg>
                                        </diV>
                                        <div class="name col">Pedido <br>{{$quantity}} {{$msg}}</div>
                                        <div class="total col">@if ($coinClient == 0) $ @else Bs @endif {{$total}}</div>
                                        <input type="hidden" id="totalProductService" value="{{$total}}">
                                        <input type="hidden" id="percentageSelect">
                                    </div>
                                    <div class="row sales justify-content-center align-items-center minh-10">
                                        <div class="col-md-2 col-sm-2 col-3">
                                            <img src="/images/envios.png" class="figure-img img-fluid rounded" width="50px" height="50px">
                                        </diV>
                                        <div class="name col">Envio</div>
                                        <div class="col total showShipping"></div>
                                    </div>
                                    <div class="showPercentage"></div>
                                    <div class="totalGlobal"></div>

                                </div> 

                                <div class="row">&nbsp;</div>
                                
                                <div class="form-navigation bottom">
                                    <button type="button" class="next btn btn-bottom">Siguiente</button>
                                    <button type="button" class="pay btn btn-bottom">Pagar</button>
                                    <button type="submit" class="btn btn-bottom">Realizar Pago</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>

    <script> 
        $(function(){
            $("#discount").on("input", function(){
                $.ajax({
                    url: "{{ url('/verify')}}", 
                    data: {"input" : $(this).val(), "user_id" : "{{$commerce->user_id}}"},
                    type: "POST",
                    dataType: "json",
                }).done(function(result){
                    $('#percentageSelect').val(result['percentage']);
                    $('.next').show();       
                    },
                ).fail(function(result){  
                    $('#percentageSelect').val(0);
                    $('.next').hide();                    
                },);
            });
            
        });
    </script>
</body>
</html>