<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleStore.css') }}">
    <script src="{{ asset('js/formStore.js') }}"></script>
    <script src="{{ asset('js/i18n/es.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
</head>
<body>
    <div class="loader"></div>
    <Section>
        <div class="container">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="row colorGrey">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-2 col-12 text-right d-block d-sm-none" id="btnFloatingShipping">
                                    <div class="relative">
                                        <div> <img class="logoCart" widht="70px" height="70px" class="justify-content-center" src="{{ asset('images/logoCarrito-white.png')}}"> </div>
                                        <div class="circleGreen">0</div>
                                    </div>
                                </div>
                                <div class="form-navigation float-left col-md-2 col-2">
                                    <button type="button" class="previous btn">
                                        <svg width="45px" height="45px" viewBox="0 0 16 16" class="bi bi-chevron-left" fill="currentColor">
                                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="float-left col-md-8 col">
                                    <h5 class="form-store" id="form-store"> {{$commerce->name}} </h5>
                                </div>
                                <div class="col-md-2 col-3 text-right d-none d-sm-block" id="btnFloatingShipping">
                                    <div class="relative">
                                        <div> <img class="logoCart" widht="70px" height="70px" class="justify-content-center" src="{{ asset('images/logoCarrito-white.png')}}"> </div>
                                        <div class="circleGreen">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row hrWhite"></div>

                    <div class="row colorGrey" id="barMenu">
                        <a class="menuStore" href="javascript:;" id="titleMenu"><i class="fa fa-bars" aria-hidden="true"></i> MENU </a>
                    </div>
                    
                    <div class= "form-section-store">
                        <div class="col-md-12 col-sm-12 col-12">
                            <ul class="nav flex-column" id="showMenu" >
                                <li class="nav-item">
                                    <a class="nav-link button-store" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-TIENDA.png') }}" width="40px" height="40px">
                                        <label>Tienda</label>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link perfilStore" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-PERFIL.png') }}" width="40px" height="40px">
                                        <label>Perfil del vendedor</label>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link button-payment" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-METODOS-DE-PAGO.png') }}" width="40px" height="40px">
                                        <label>Método de pago</label>
                                    </a>
                                </li>
                                @if($statusShipping)
                                <li class="nav-item">
                                    <a class="nav-link button-shipping" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-ENVIOS.png') }}" width="40px" height="40px">
                                        <label>Envíos</label>
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link button-whatsapp" href="https://api.whatsapp.com/send/?phone={{$whatsappNum}}">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-CENTRO-DE-MENSAJERIA.png') }}" width="40px" height="40px">
                                        <label>Centro de mensajería</label>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link button-categories" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-CATEGORIAS.png') }}" width="40px" height="40px">
                                        <label>Categorías</label>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link button-closeMenu" href="javascript:;">
                                        <img class="rounded-circle" src="{{ asset('images/ICON-CERRAR-SESION.png') }}" width="40px" height="40px">
                                        <label>Salir del menú</label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">&nbsp;</div>
                    <div class= "form-section-store center" id="showProduct">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="profile">
                                @if($picture)
                                    <img class="rounded-circle" src="{{$picture->url}}" width="150px" height="150px">
                                @else
                                    <img class="rounded-circle" src="{{ asset('images/perfilUser.png') }}" width="150px" height="150px">
                                @endif
                                </div>
                            </div>
                            <div class="row" style="curso:point;">
                                <div class="divisaExpanded hide">
                                    <a href="javascript:;" id="btnEEUU">
                                        <img class="rounded-circle" style="margin:5px;" src="{{ asset('images/eeuu.png') }}" width="30px" height="30px">
                                        <br>
                                        <div class="textDivisa">USA $</div>
                                    </a>
                                    <a href="javascript:;" id="btnVE">
                                        <img class="rounded-circle" style="margin:5px;" src="{{ asset('images/venezuela.png') }}" width="30px" height="30px">
                                        <br>
                                        <div class="textDivisa">VE Bs</div>
                                    </a>
                                </div>
                                <div class="divisa">
                                    <a href="javascript:;" id="btnFloating" ><img class="rounded-circle" style="margin:5px;" src="{{ asset('images/divisa.png') }}" width="40px" height="40px"></a>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="coinClient"  name="coinClient" value="{{$coinClient}}">
                        <input type="hidden" id="userUrl"  name="userUrl" value="{{$userUrl}}">

                        <div class="row">&nbsp;</div>

                        <div class= "center" id="showProduct">
                            <div class="row hrGrey" id="showProductHr"></div>
                            <div class="row" id="ProductsServices">
                                <div class="col">
                                    <button type="button" class="btn btnProductsServices btn-current" id="btn-products">Productos</button>
                                </div>
                                @if($services > 0)
                                <div class="col">
                                    <button type="button" class="btn btnProductsServices" id="btn-services">Servicios</button>
                                </div>
                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row categories" id="showProductsServices"></div>
                        </div>
                    </div>

                    <div class= "form-section-store">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="profile">
                                @if($picture)
                                    <img class="rounded-circle" src="{{$picture->url}}" width="150px" height="150px">
                                @else
                                    <img class="rounded-circle" src="{{ asset('images/perfilUser.png') }}" width="150px" height="150px">
                                @endif
                                </div>
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>

                            <div class="col-12">
                                <p><strong>Rif: </strong> {{$commerce->rif}}</p>
                                <p><strong>Teléfono: </strong> {{$commerce->phone}}</p>
                                <p><strong>Dirección: </strong> {{$commerce->address}}</p>
                            </div>

                        </div>
                    </div>

                    <div class= "form-section-store center">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 mx-auto text-center">
                                <label class="titleCoin">Moneda</label>
                            </div>
                            <div class="row">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-12 mx-auto text-center">
                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/monedas.png') }}">

                            </div>

                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 mx-auto text-center">
                                <label class="titleCoin">Aceptamos</label>
                            </div>
                            <div class="row">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-12 mx-auto text-center">
                                <img class="img-fluid" alt="Responsive image" src="{{ asset('images/logos-bancos.png')}}" >
                            </div>

                        </div>
                        
                    </div>

                    @if (count($shippings)!=0)
                    <div class="form-section-store">
                        <div class="col-11" style="margin:auto;">
                            <input type="hidden" id="statusShipping" value="true">
                            @foreach ($shippings as $shipping)
                                <div class="row shippings justify-content-center align-items-center minh-10">
                                    <div class="description-shippings col">{{$shipping->description}}</div>
                                    @if ($coinClient == 0) 
                                        <div class="shipping-price col-6"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                    @else
                                        <div class="shipping-price col-6"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                        <input type="hidden" id="statusShipping" value="false">
                    @endif

                    <div class= "form-section-store">
                        <div class="row categories" id="showCategories"></div>
                    </div>

                    <div class="row">&nbsp;</div>
                </div>
            </div>
        </div>

        <div class="row fixed-item-payment">
            <div class="col-md-6 col-sm-12 col-12 mx-auto text-center">
                <div class="form-navigation bottom relative">
                    <button type="submit" id="totalBtn" class="submit btn btn-bottom statusButton">Pagar</button>
                </div>
                <div class="row justify-content-center" id="loading">
                    <img widht="80px" height="80px" class="justify-content-center" src="{{ asset('images/loadingTransparent.gif').'?v='.time()  }}">
                </div>
            </div>
        </div>
    </Section>


    <script>
        var commerceName = "{{$commerce->name}}";
        var clientType = 0
        var categorySelect = null;
        var rateToday = {{$rate}};
        var coinClient = $('#coinClient').val();

        function showCategories(type)
        {   
            $( ".loader" ).fadeIn(150, function() {
                $( ".loader" ).fadeIn("slow"); 
            }); 

            clientType = type

            $.ajax({
                url: "{{ route('show.categories')}}", 
                data: {"type" : type, "commerce_id" : "{{$commerce->id}}"},
                type: "POST",
                dataType: "json"
            }).done(function(data){
                $('#showCategories').html(data.html);
                showProductsServices(null);  
            }).fail(function(){  
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#showCategories').html();                 
            });
        }

        function showProductsServices(_categorySelect){
            categorySelect = _categorySelect;

            $.ajax({
                url: "{{ route('show.productsServices')}}", 
                data: {"type" : clientType, "commerce_id" : "{{$commerce->id}}", "category_select" : _categorySelect, "coinClient" : coinClient, "user_id": "{{$commerce->user_id}}"},
                type: "POST",
                dataType: "json"
            }).done(function(data){
                $('#showProductsServices').html(data.html);
                setTimeout(removeLoader, 100);
            }).fail(function(){  
                location.reload();             
            });
        }

        function removeLoader(){
            $( ".loader" ).fadeOut(500, function() {
                $( ".loader" ).fadeOut("slow"); 
            });  
        }

        function sendData(){
            $.ajax({
                url: "{{route('sale.newSale')}}", 
                data: {"commerce_id" : "{{$commerce->id}}", "user_id" : "{{$commerce->user_id}}", "sales" : listCart, "coin": coinClient, "rate": rateToday, "nameClient":"Tienda Web", "statusShipping":$('#statusShipping').val(), "descriptionShipping": "", "userUrl": "{{$commerce->userUrl}}" },
                type: "POST",
            }).done(function(result){
                window.location=result.url;
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
            });
        }
    </script>
</body>
</html>