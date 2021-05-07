@php
    use Carbon\Carbon;
@endphp
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