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
                                    <div class="row">&nbsp;</div>
                                    <h3> Por </h3>
                                    <div class="row">&nbsp;</div>
                                    @foreach ($sales as $sale)
                                        <div class="row sales">
                                            <div class="quantity col-md-2 col-sm-2 col-3"><div id="desingQuantity">{{$sale->quantity}}</div></div>
                                            <div class="name col">{{$sale->name}} - <script> document.write(showPrice("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}))</script></div>
                                            <div class="total col"><script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class= "form-section">
                                    <p>Ingrese un correo electrónico donde podamos enviarte el recibo de pago:</p>
                                    <label for="email">CORREO ELECTRÓNICO</label>
                                    <input type="email" name="email" class="form-control" placeholder="joedoe@gmail.com" required />
                                </div>

                                @if (count($shippings)!=0)
                                <script> statusShipping = true;</script>
                                <div class="form-section">
                                    @foreach ($shippings as $shipping)
                                        <div class="row shippings">
                                            <div class="col-md-2 col-sm-2 col-2" id="iconChecked">
                                                <input type="radio" class="radio-shippings" name="shippings" id="shippings">
                                            </div>
                                            <div class="description-shippings col">{{$shipping->description}}</div>
                                            <div class="shipping-price col">@if($shipping->price == "FREE") Gratis @else <script> document.write(showPrice({{$shipping->price}}, {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script> @endif</div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                @if (count($shippings)!=0)
                                <div class="form-section">
                                    <p> Ingresa la dirección de envío:</p>
                                    <label for="name">NOMBRE:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Joe Doe" required />
                                    <label for="number">NUMERO DE CELULAR:</label>
                                    <input type="number" name="number" class="form-control" placeholder="04121234567" required />
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
                                    <input type="text" name="nameCard" class="form-control" placeholder="Joe Doe" required />
                                    <label for="numberCard">NUMERO DE LA TARJETA:</label>
                                    <input type="number" name="numberCard" class="form-control" placeholder="4012888888881881" required />
                                    <div class="row">
                                        <div class="col">
                                            <label for="dateCard">FECHA DE EXPIRACIÓN:</label>
                                            <input type="number" name="dateCard" class="form-control" placeholder="MM/AA" required />
                                        </div>
                                        <div class="col">
                                            <label for="cvcCard">CVV/CVC:</label>
                                            <input type="number" name="cvcCard" class="form-control" placeholder="123" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                
                                <div class="form-navigation bottom">
                                    <button type="button" class="next btn btn-bottom">Siguiente</button>
                                    <button type="button" class="pay btn btn-bottom">Pagar</button>
                                    <button type="submit" class="btn btn-bottom">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>
</body>
</html>