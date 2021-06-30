<div class="fade has-success" id="pills-cryptocurrencies" role="tabpanel" aria-labelledby="pills-cryptocurrencies-tab">
    <div class="row">
        <h4 class="mx-auto">Billetera (Wallet)</h4>
    </div>
    <div class="row d-flex justify-content-end buttonAdd">
        <div class="col">
            <button type='button' class="btn btn-bottom" onclick="showWallet(0)"><i class="material-icons">edit</i> Crear Billetera</button>
        </div>
    </div>
    <table id="table_crypto" class="table table-bordered display" style="width:100%;">
        <thead>
            <tr class="table-title">
                <th scope="col">Nombre</th>
                <th scope="col">Dirección (Wallet)</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listCryptocurrencies as $cryptocurrency)
                <tr>
                    <td>
                        <img src="{{ asset('cryptocurrencies/images/'.$cryptocurrency->baseAsset.'.png') }}" width="50px">
                        {{ $cryptocurrency->name }}
                    </td>
                    <td>{{ $cryptocurrency->address }}</td>
                    <td>
                        @if($cryptocurrency->publish == 0)
                            <div class="pending">SIN PUBLICAR</div>
                        @else
                            <div class="completed">PUBLICADO</div>
                        @endif
                    </td>
                    <td>
                        <botton class="pay btn btn-bottom" onclick="showWallet({{$cryptocurrency->id}}, true)" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Editar"><i class="material-icons">edit</i></botton>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div id="showWallet"></div>
</div>