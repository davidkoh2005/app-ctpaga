<script src="../js/i18n/es.js"></script>
<script src="../js/global.js"></script>
@if($products != null)
    @foreach($products as $product)
    <div class="card" style="width: 12rem;">
        <img class="card-img-top" src="{{$product->url}}">
        <div class="card-body">
            <h5 class="card-title">{{$product->name}}</h5>
            <p class="card-text"><small class="text-muted">@if($product->description != null) {{$product->description}} @endif</small></p>
            <p class="card-text"><!-- <script> document.write(showPrice("{{$product->price}}", {{$rate}}, {{$product->coin}}, 0))</script> --></p>
            <button type="button" class="btn btn-bottom">Agregar al carrito</button>
        </div>
    </div>
    @endforeach
@else
    @foreach($services as $services)
    <div class="card" style="width: 12rem;">
        <img class="card-img-top" src="{{$services->url}}">
        <div class="card-body">
            <h5 class="card-title">{{$services->name}}</h5>
            <p class="card-text"><small class="text-muted">@if($services->description != null) {{$service->description}} @endif</small></p>
            <p class="card-text"><!-- <script> document.write(showPrice("{{$service->price}}", {{$rate}}, {{$service->coin}}, 0))</script> --></p>
            <button type="button" class="btn btn-bottom">Agregar al carrito</button>
        </div>
    </div>
    @endforeach
@endif