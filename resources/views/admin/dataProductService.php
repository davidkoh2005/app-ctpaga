@foreach ($sales as $sale)
    <div class="row sales justify-content-center align-items-center minh-10" id="listSale">
        <div class="quantity col-md-2 col-sm-2 col-3"><div id="desingQuantity">{{$sale->quantity}}</div></div>
        <input type="hidden" name="idSale" id="idSale" value="{{$sale->id}}">
        <div class="name col">{{$sale->name}}<br> <script> document.write(showPrice("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}))</script></div>
        @if ($coinClient == 0) 
            <div class="total col"><script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
        @else
            <div class="total bold col-12 d-block d-sm-none"> Total:</div>
            <div class="total col-md col-12"> <script> document.write(showTotal("{{$sale->price}}", {{$rate}}, {{$sale->coin}}, {{$coinClient}}, {{$sale->quantity}}))</script></div>
        @endif
    </div>
@endforeach