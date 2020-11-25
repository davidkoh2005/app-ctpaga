<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctpaga</title>
    @include('library')
    <link rel="stylesheet" type="text/css" href="../css/styleForm.css">
    <script src="../js/form.js"></script>  
<body>
    <Section>
        <div class="container">
            <div class="Row">
                <div class="col-md-6 col-sm-12 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header text-white bg-info">
                            <h5 id="form-sales"> Ventas </h5>
                        </div>
                        <div class="card-body">
                            <form action="" class="contact-form">
                                @csrf
                                <div class= "form-section">
                                    <img class="rounded-circle" src="{{ $picture->url}}" width="200px" height="200px">
                                    <h3> {{$commerce->name}}</h3>
                                    <div class="row">&nbsp;</div>
                                    <span id="coin">@if ($coinClient == 0) $ @else Bs @endif</span> <span id="total"> {{$total}}</span>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">&nbsp;</div>
                                    <h3> Por </h3>
                                    <div class="row">&nbsp;</div>
                                    <div data-spy="scroll" data-target="#example" data-offset="0">
                                        @foreach ($sales as $sale)
                                            <div class="row sales">
                                                <div class="quantity col-md-1 col-sm-1 col-1">{{$sale->quantity}}</div>
                                                <div class="name col-md-6 col-sm-6 col-6">{{$sale->name}}</div>
                                                <div class="price col-md-5 col-sm-5 col-5">50</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">&nbsp;</div>
                                </div>
                                <div class= "form-section">
                                    <label for="firstname">First Name:</label>
                                    <input type="text" name="firstname" class="form-control" required />
                                </div>

                                <div class="form-section">
                                    <label for="email">Email:</label>
                                    <input type="text" name="email" class="form-control" required />
                                </div>

                                <div class="form-section">
                                    <label for="password">Password:</label>
                                    <input type="text" name="password" class="form-control" required />
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="previous btn btn-info float-left">Atrás</button>
                                    <button type="button" class="next btn btn-info float-right">Siguiente</button>
                                    <button type="button" class="pay btn btn-info float-right">Pagar</button>
                                    <button type="submit" class="btn btn-success float-right">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>
</body>
</html>