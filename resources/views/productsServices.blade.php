@if($products != null)
    @if(count($products) != 0)
        @foreach($products as $product)
        <div class="col-md-6 col-12 justify-content-start productService" style="margin-bottom:20px;">
            <div class="card h-100">
                <img class="card-img-top" style="padding:10px;" height="250px" width="260px" src="{{$product->url}}">
                <div class="card-body">
                    <h5 class="card-title">{{$product->name}}</h5>
                    <p class="card-text">@php showPrice($product->price, $rate, $product->coin, $coinClient); @endphp</p>
                </div>
                <div class="card-flotter flotterAddCart">
                    <button type="button" class="btn btn-bottom" onclick="addCart({{$product}}, 0)">Agregar al carrito</button>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col justify-content-center mx-auto">
            <div class="card">
                <h5 class="card-title">No hay Producto</h5>
            </div>
        </div>
    @endif
@else
    @if(count($services) != 0)
        @foreach($services as $service)
        <div class="col-md-6 col-12 justify-content-start productService">
            <div class="card h-100">
                <img class="card-img-top" style="padding:10px;" height="250px" width="260px" src="{{$services->url}}">
                <div class="card-body">
                    <h5 class="card-title">{{$service->name}}</h5>
                    <p class="card-text">@php showPrice($service->price, $rate, $service->coin, $coinClient); @endphp</p>
                </div>
                <div class="card-flotter flotterAddCart">
                <button type="button" class="btn btn-bottom" onclick="addCart({{$service}}, 1)">Agregar al carrito</button>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col justify-content-center mx-auto">
            <div class="card">
                <h5 class="card-title">No hay Servicio</h5>
            </div>
        </div>
    @endif

@endif

@php
    function showPrice($price, $rate, $coin, $coinClient){
        if ($price == "FREE")
            echo "GRATIS";
        else if ($coinClient == 0)
            echo "$ ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
        else
            echo "Bs ".number_format(exchangeRate($price, $rate, $coin, $coinClient), 2, ',', '.');
    }

    function exchangeRate($price, $rate, $coin, $coinClient){
        $result = 0;

        if($coin == 0 && $coinClient == 1)
            $result = (floatval($price) * $rate);
        else if($coin == 1 && $coinClient == 0)
            $result = (floatval($price) / $rate);
        else
            $result = (floatval($price));

        return $result;
    }
@endphp