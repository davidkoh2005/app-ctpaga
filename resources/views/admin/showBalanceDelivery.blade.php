<input type="hidden" name="deliveryID" id="deliveryID" value='{{$delivery->id}}'>
<div id="title"><h5 class="center">Balance </h5></div>
<h2 class="center">$ {{number_format(($balance), 2, ',', '.')}} </h2>

<div class="row">&nbsp;</div>

@if(count($cashes) >0)
    <div id="title"><h5 class="center">Pedido </h5></div> <br>
@endif

@foreach ($cashes as $cash)
    <div class="row sales justify-content-center align-items-center minh-10" id="listSale" style="cursor: context-menu; margin-left: 10px; margin-right: 10px;">
        <div class="name col">{{$cash->codeUrl}}</div>
        <div class="verticalLine"></div>
        <div class="total" style="padding-bottom:13px" >$ {{number_format((floatval($cash->total)), 2, ',', '.')}}</div>
    </div>
@endforeach
@if(count($cashes) >0)
    <div class="row">&nbsp;</div>
    <div class="marginAuto">
        <input type="button" class="btn btn-bottom btn-current" id="updatePayment" value="Pagado">
        <div class="row marginAuto hide" id="loading">
            <img widht="80px" height="80px" class="justify-content-center marginAuto" src="{{ asset('images/loadingTransparent.gif')  }}">
        </div>
    </div>
    <div class="row">&nbsp;</div>
@endif