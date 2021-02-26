@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}

Gracias por realizar la compra. EL pago esta en proceso de Verificación!<p></p>

<br>
<strong>Númuero de compra: </strong> {{$codeUrl}}
<br>
Para ver mas Producto o Servicio de <strong>{{$name}}</strong> ingrese a la tienda online.
@component('mail::button', ['url'=> $url])
    Tienda
@endcomponent


{{-- Subcopy --}}
@isset($url)
@slot('subcopy')
@lang(
    "Si tiene problemas para hacer clic en el botón, copia y pega la URL a continuación \n ".
    'en su navegador web:',
    [
        'url' => $url,
    ]
) <span class="break-all">{{ $url }}</span>
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('Todos los derechos reservados.')
@endcomponent
@endslot
@endcomponent
