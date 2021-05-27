@php
    use Carbon\Carbon;
@endphp
<div id="title"><h5 class="center">Información Completa</h5></div>
<label><strong>Nombre de Cliente: </strong>{{$transaction->nameClient}}</label> <br>
<label><strong>Correo: </strong>{{$transaction->email}}</label> <br>

<div class="row">&nbsp;</div>
<div id="title"><h5 class="center">Información quien recibe el Productos y/o Servicios </h5></div>
<label><strong>Nombre: </strong>{{$transaction->nameShipping}}</label> <br>
<label><strong>Teléfono: </strong>{{$transaction->numberShipping}}</label> <br>
<label><strong>Dirección: </strong>{{$transaction->addressShipping}}</label> <br>
<label><strong>Detalle: </strong>{{$transaction->detailsShipping}}</label> <br>

<div class="row">&nbsp;</div>
<div id="title"><h5 class="center">Delivery </h5></div>
<label><strong>Nombre: </strong>@if($delivery) {{$delivery->name}} @else NO ASIGNADO @endif</label> <br>
<div style="text-align:justify;"> <label><strong>Estado: </strong>
    @if($transaction->statusDelivery==1 && $transaction->timeDelivery != null && $transaction->timeDelivery <= Carbon::now()) 
        <span class="urgentDelivery">Urgente</span> 
    @elseif($transaction->statusDelivery == 0) 
        <span class="pendingDelivery">Pendiente</span> 
    @elseif($transaction->statusDelivery == 1) 
        <span class="publicDelivery">Publicado</span> 
    @elseif($transaction->statusShipping == 0) 
        <span class="inTransit">En tránsito</span>  
    @elseif($transaction->statusShipping == 1) 
        <span class="RetiredProduct">Producto Retirado</span>  
    @elseif($transaction->statusDelivery == 2) 
        <span class="completedDelivery">Entregado</span>  
    @endif 
    </label>
</div>

<div class="row">&nbsp;</div>
<div id="title"><h5 class="center">Envio Seleccionado </h5></div>
<label><strong>Descripción: </strong>@if($transaction->selectShipping != null) {{$transaction->selectShipping}} @else 0 @endif</label> <br>
<label><strong>Precio: </strong>@if($transaction->coin == 0) $ @else Bs @endif @if($transaction->priceShipping != null) {{number_format(floatval($transaction->priceShipping), 2, ',', '.')}} @else 0 @endif</label> <br>

<div class="row">&nbsp;</div>
<div id="title"><h5 class="center">Productos y/o Servicios </h5></div>
@foreach ($sales as $sale)
    <div class="row sales justify-content-center align-items-center minh-10" id="listSale" style="cursor: context-menu;">
        <div class="quantity"><div id="desingQuantity">{{$sale->quantity}}</div></div>
        <div class="verticalLine"></div>
        <input type="hidden" name="idSale" id="idSale" value="{{$sale->id}}">
        <div class="name col">{{$sale->name}}<br> @php showPrice($sale->price, $rate, $sale->coin, $coinClient); @endphp</div>
        <div class="verticalLine"></div>
        <div class="total" style="padding-bottom:13px" > @php showTotal($sale->price, $rate, $sale->coin, $coinClient, $sale->quantity); @endphp</div>
    </div>
@endforeach
<div style="text-align:right;"><label><strong>Descuento (%): </strong>{{$transaction->percentage}}</label> </div>
<div style="text-align:right;"><label><strong>Total: </strong>@if($transaction->coin == 0) $ @else Bs @endif {{number_format($transaction->total, 2, ',', '.')}}</label> </div>


@php
    function showTotal($price, $rate, $coin, $coinClient, $quantity){
        $result = exchangeRate($price, $rate, $coin, $coinClient);

        if ($coinClient == 0)
            echo "$ ".number_format(($result * $quantity), 2, ',', '.');
        else
            echo "Bs ".number_format(($result * $quantity), 2, ',', '.');

    }

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