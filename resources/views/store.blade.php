<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../css/styleStore.css">
    <script src="../../js/formStore.js"></script>
    <script src="../../js/i18n/es.js"></script>
    <script src="../../js/global.js"></script>
</head>
<body>
    <div class="loader"></div>
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
                                    <h5 class="form-store"> Tienda </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="coinClient"  name="coinClient" value="{{$coinClient}}">
                            <input type="hidden" id="userUrl"  name="userUrl" value="{{$userUrl}}">

                            <div class= "form-section-store center">
                                <img class="rounded-circle" src="{{$picture->url}}" width="100px" height="100px">
                                <h3> {{$commerce->name}}</h3>
                                <div class="button-circle">
                                    <ul>
                                        <li> <a href="https://api.whatsapp.com/send/?phone={{env('WHATSAPP_NUM')}}"> <i class="fa fa-whatsapp" aria-hidden="true"></i><p>Whatsapp</p></a></li>
                                        <li> <a href="#"> <i class="fa fa-truck button-shipping" aria-hidden="true"></i><p class="shipping">Envíos</p></a> </li>
                                    </ul>
                                </div>
                                <div class="row">&nbsp;</div>

                                <div class="row" id="ProductsServices">
                                    <div class="col">
                                        <button type="button" class="btn btn-bottom btn-current" id="btn-products">Productos</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-bottom" id="btn-services">Servicios</button>
                                    </div>
                                </div>

                                <hr class="category">

                                <div class="row categories bold">Categorías</div>
                                <div class="row">&nbsp;</div>
                                <div class="row categories" id="showCategories"></div>
                                <div class="row">&nbsp;</div>
                                <hr>
                                <div class="row categories bold" id="showTitleProductsServices"></div>
                                <div class="row">&nbsp;</div>
                                <div class="row categories" id="showProductsServices"></div>
                            </div>

                            @if (count($shippings)!=0)
                            <div class="form-section-store">
                                <input type="hidden" id="statusShipping" value="true">
                                <p> Metodo de envío:</p>
                                @foreach ($shippings as $shipping)
                                    <div class="row shippings justify-content-center align-items-center minh-10">
                                        <div class="description-shippings col">{{$shipping->description}}</div>
                                        @if ($coinClient == 0) 
                                            <div class="shipping-price col"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                        @else
                                            <div class="shipping-price col-md col-12"><script> document.write(showPrice("{{$shipping->price}}", {{$rate}}, {{$shipping->coin}}, {{$coinClient}}))</script></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @else
                                <input type="hidden" id="statusShipping" value="false">
                            @endif

                            <div class="row">&nbsp;</div>

                            <div class="form-navigation bottom">
                                <button type="button" id="totalBtn" class="submit btn btn-bottom statusButton">Pagar</button>
                            </div>
                            <div class="row justify-content-center"id="loading">
                                <img widht="80px" height="80px" class="justify-content-center" src="../images/loading.gif">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>

    <div class="row">
        <div class="col text-right fixed-item" id="btnFloating">
            <video id="videoBs" class="btnFloating" width="100px">
                <source src="../videos/botonBs.mp4" type="video/mp4">
            </video>
            <video id="videoUSD" class="btnFloating" width="100px">
                <source src="../videos/botonUSD.mp4" type="video/mp4">
            </video>
        </div>
    </div>

    <div class="row">
        <div class="col text-right fixed-item-cart" id="btnFloatingShipping">
            <div class="relative">
                <div> <img class="logoCart" widht="80px" height="80px" class="justify-content-center" src="../images/logoCarrito.png"> </div>
                <div class="circleGreen">0</div>
            </div>
        </div>
    </div>

    <script>
        var ClientType = 0;
        var categorySelect = null;
        var rateToday = {{$rate}};
        function showCategories(type)
        {   
            $( ".loader" ).fadeIn(150, function() {
                $( ".loader" ).fadeIn("slow"); 
            }); 

            ClientType = type

            if(type == 0)
                $('#showTitleProductsServices').text('Productos');
            else
                $('#showTitleProductsServices').text('Servicios');

            $.ajax({
                url: "{{ route('show.categories')}}", 
                data: {"type" : type, "commerce_id" : "{{$commerce->id}}"},
                type: "POST",
                dataType: "json"
            }).done(function(data){
                $('#showCategories').html(data.html);
                showProductsServices(null);  
            }).fail(function(){  
                $('#showCategories').html();                 
            });
        }

        function showProductsServices(_categorySelect){
            categorySelect = _categorySelect;

            $.ajax({
                url: "{{ route('show.productsServices')}}", 
                data: {"type" : ClientType, "commerce_id" : "{{$commerce->id}}", "category_select" : _categorySelect, "coinClient" : coinClient, "user_id": "{{$commerce->user_id}}"},
                type: "POST",
                dataType: "json"
            }).done(function(data){
                $('#showProductsServices').html(data.html);
                setTimeout(removeLoader, 100);
            }).fail(function(){  
                $('#showProductsServices').html();                 
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
            }).fail(function(result){});
        }
    </script>
</body>
</html>