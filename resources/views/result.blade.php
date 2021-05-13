<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
    <style>
        .btn-bottom {
            width: 100%;
        }
    </style>
<body>
    <Section>
        <div class="container result">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="card">
                        <div class="card-body center">
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <svg width='3em' height='3em' viewBox='0 0 16 16' class='bi bi-check-circle-fill' fill='currentColor'>
                                <path fill-rule='evenodd' d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
                            </svg>
                            <div class="row">&nbsp;</div>
                            @if($status)
                                <p>¡Tu pago se ha realizado correctamente!</p>
                            @else
                                <p>¡Tu pago esta en proceso de verificación!</p>
                            @endif
                            <a href="{{ route('form.store', ['userUrl' => $userUrl]) }}" class="btn btn-bottom store">Ver tienda</a>
                            <div class="row">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>


</body>
</html>