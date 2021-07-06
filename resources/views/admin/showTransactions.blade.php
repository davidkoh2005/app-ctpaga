@if($payment == 'Zelle')
    <div id="title"><h5 class="center">Zelle</h5></div>
    <div class="row">&nbsp;</div>
    <div style="text-align: initial;">
        <label><strong>Nombre de Cuenta: </strong> {{$transaction->nameAccount}}</label> <br>
        <label><strong>ID de Confirmacion: </strong> {{$transaction->idConfirm}}</label> <br>
    </div>
@elseif($payment == 'Transferencia' || $payment == 'Pago Móvil' )
    <div id="title"><h5 class="center">@if($transactions[0]->type == 0) Transferencia @else Pago Móvil @endif</h5></div>
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>
    <table id="table_id" class="table table-bordered mb-5 display">
        <thead>
            <tr class="table-title">
                <th scope="col">Banco</th>
                <th scope="col">Monto</th>
                <th scope="col">Número de transacción</th>
                <th scope="col">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->bank }}</td>
                <td>{{ $transaction->amount }}</td>
                <td> {{$transaction->transaction}}</td>
                <td> {{$transaction->date}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@elseif($payment == 'Bitcoin')
    <div id="title"><h5 class="center">Criptomoneda</h5></div>
    <div class="row">&nbsp;</div>
    <div class="row">
        <img class="rounded mx-auto d-block" src="{{asset('cryptocurrencies/images/'.$transaction->baseAsset.'.png') }}" id="showImg" width="100px">
    </div>
    <div class="row justify-content-center">
        <label>{{$transaction->name}}</label>
    </div>
    <div class="row">&nbsp;</div>
    <div style="text-align: initial;">
        <label><strong>Price: </strong> 1 {{$transaction->baseAsset}} <img src="{{asset('images/right-arrow.png')}}" width="35px"> $ {{number_format(floatval($transaction->price_cryptocurrency), 2, '.', ',')}}</label> <br>
        <label><strong>Total a Pagar: </strong> {{number_format((floatval($transaction->total) / floatval($transaction->price_cryptocurrency)), 8, '.', ',') }} {{$transaction->baseAsset}}</label> <br>
        <label><strong>Hah de Transaccion: </strong> {{$transaction->hash}}</label> <br>
    </div>
@endif