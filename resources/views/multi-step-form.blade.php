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
                                            <div class="quantity col-md-1 col-sm-1 col-1">{{$sale->quantity}}</div>
                                            <div class="name col">{{$sale->name}}</div>
                                            <div class="price col"><script> document.write(showPrice({{$sale->price}}, {{$rate}}, {{$sale->coin}}, {{$coinClient}}))</script></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class= "form-section">
                                    <p>Ingrese un correo electrónico donde podamos enviarte el recibo de pago:</p>
                                    <label for="email">CORREO ELECTRÓNICO</label>
                                    <input type="email" name="email" class="form-control" placeholder="joedoe@gmail.com" required />
                                </div>

                                @if (count($shippings)!=0)
                                <div class="form-section">
                                    @foreach ($shippings as $shipping)
                                        <div class="row shippings">
                                            <div class="col-md-2 col-sm-2 col-2">
                                                <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                                <input type="radio" class="radio-shippings" name="shippings" id="shippings">
                                            </div>
                                            <div class="description-shippings col">{{$shipping->description}}</div>
                                            <div class="price col">@if($shipping->price == "FREE") Gratis @else <script> document.write(showPrice({{$shipping->price}}, {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script> @endif</div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                <div class="form-section">
                                    <label for="password">Password:</label>
                                    <input type="text" name="password" class="form-control" required />
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