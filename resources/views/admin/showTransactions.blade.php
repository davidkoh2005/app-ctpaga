@isset($zelle)
<div id="title"><h5 class="center">Zelle</h5></div>
<div class="row">&nbsp;</div>
<div style="text-align: initial;">
    <label><strong>Nombre de Cuenta: </strong> {{$zelle->nameAccount}}</label> <br>
    <label><strong>ID de Confirmacion: </strong> {{$zelle->idConfirm}}</label> <br>
</div>
@else
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
@endif