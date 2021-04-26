  <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" style="background-color: white;">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo" style="background-color: white;"><a href="{{route('admin.dashboard')}}" class="simple-text logo-normal">
        <img src="{{ asset('images/logo/logo.png') }}" alt="image" width="160px" height="60px">
        </a>
      </div>
      <div class="sidebar-wrapper" style="background-color: white;">
        <ul class="nav">
          @if(Auth::guard('admin')->check())
            <li class="nav-item" id="nav-dashboard">
              <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="material-icons">dashboard</i>
                <p>Inicio</p>
              </a>
            </li>
            <li class="nav-item" id="nav-users">
              <a class="nav-link" href="{{route('admin.listUsers')}}">
                <i class="material-icons">manage_accounts</i>
                <p>Usuarios</p>
              </a>
            </li>
            <li class="nav-item" id="nav-balance">
              <a class="nav-link" href="javascript:;">
                <i class="material-icons">account_balance</i>
                Depositos
              </a>
              <ul class="nav hide" id="subMenuDeposits" >
                <li class="nav-item">
                  <form id="formUSA"  method='POST' action="{{route('admin.balance')}}">
                    <input type="hidden" name="selectCoin" value="0">
                    <a href="javascript:$('#formUSA').submit();" class="nav-link">
                      <img src="{{ asset('images/eeuu.png') }}" width="20px" height="20px">
                      <label class="coinText">Moneda USA $</label>
                    </a>
                  </form>
                </li>
                <li class="nav-item">
                  <form id="formVE" method='POST' action="{{route('admin.balance')}}"> 
                    <input type="hidden" name="selectCoin" value="1">
                    <a href="javascript:$('#formVE').submit();" class="nav-link">
                      <img src="{{ asset('images/venezuela.png') }}" width="20px" height="20px">
                      <label class="coinText">Moneda VE Bs</label>
                    </a>
                </form>
                </li>
                <li class="nav-item" id="nav-reportPayment">
                  <a class="nav-link" href="{{route('admin.reportPayment')}}">
                    <i class="material-icons">request_quote</i>
                    <p>Reporte Depósitos</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item" id="nav-court">
              <a href="javascript:;" class="nav-link">
              <i class="material-icons">price_check</i>
                <p>Corte Depositos</p>
              </a>
            </li>
            <li class="nav-item" id="nav-commerces">
              <a class="nav-link" href="{{route('admin.commerces')}}">
                <i class="material-icons">store</i>
                <p>Comerciantes</p>
              </a>
            </li>
            <li class="nav-item" id="nav-transactions">
              <a class="nav-link" href="{{route('admin.transactions')}}">
                <i class="material-icons">description</i>
                <p>Transacciones</p>
              </a>
            </li>
            <li class="nav-item" id="nav-delivery">
              <a class="nav-link" href="{{route('admin.delivery')}}">
                <i class="material-icons">local_shipping</i>
                <p>Delivery</p>
              </a>
            </li>
            <li class="nav-item" id="nav-rate">
              <a class="nav-link" href="{{route('admin.showRate')}}">
              <i class="material-icons">attach_money</i>
                <p>Tasas</p>
              </a>
            </li>
            <li class="nav-item" id="nav-auth-delivery">
              <a href="{{route('admin.authDelivery')}}" class="nav-link">
              <i class="material-icons">verified_user</i>
                <p>Delivery Autorizado</p>
              </a>
            </li>
            <li class="nav-item" id="nav-historyCashes">
              <a href="{{route('admin.historyCashes')}}" class="nav-link">
              <i class="material-icons">description</i>
                <p>Historial Efectivo</p>
              </a>
            </li>
            <li class="nav-item" id="nav-settings">
              <a href="{{route('admin.settings')}}" class="nav-link">
              <i class="material-icons">settings</i>
                <p>Configuraciones</p>
              </a>
            </li>
          @else
            <li class="nav-item" id="nav-dashboard">
              <a class="nav-link" href="{{route('commerce.dashboard')}}">
                <i class="material-icons">dashboard</i>
                <p>Inicio</p>
              </a>
            </li>
            <li class="nav-item" id="nav-transactions">
              <a class="nav-link" href="{{route('commerce.transactions')}}">
                <i class="material-icons">description</i>
                <p>Transacciones</p>
              </a>
            </li>
            <li class="nav-item" id="nav-rateHistory">
              <a class="nav-link" href="{{route('commerce.rate')}}">
              <i class="material-icons">attach_money</i>
                <p>Tasas</p>
              </a>
            </li>
            <li class="nav-item" id="nav-depositHistory">
              <a class="nav-link" href="{{route('commerce.depositHistory')}}">
                <i class="material-icons">description</i>
                <p>Historial</p>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{route('logout')}}">
            <i class="material-icons">login</i>
              <p>Salir</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <style>
      .ajs-message {
        color: white !important;
      }
      button.ajs-button.ajs-ok {
          border-radius: 60px !important;
          border: 2px solid #00cc5f !important;
          background-color: #00cc5f !important;
          color: white !important;
      }

      button.ajs-button.ajs-ok:hover {
          border-radius: 60px !important;
          background-color: white !important;
          border: 2px solid #00cc5f !important;
          color: #00cc5f !important;
      }

      button.ajs-button.ajs-cancel {
          border-radius: 60px !important;
          border: 2px solid #ffffff80 !important;
          color: black !important;
      }

      button.ajs-button.ajs-cancel:hover {
          border-radius: 60px !important;
          background-color: #ffffff80 !important;
          border: 2px solid #ffffff80 !important;
          color: black !important;
      }
    </style>
@if(Auth::guard('admin')->check())
  <script>
    setInterval(verifyAlarm, 30000);

    function verifyAlarm(){
      $.ajax({
        url: "{{route('admin.verifyAlarm')}}", 
        type: "POST",
        }).done(function(data){
            if(data.status == 201){
              var audio = new Audio("{{asset('sounds/alarma.mp3')}}"); 
              audio.play();
              audio.loop=true; 
              audio.volume = 1;

              alertify.alert('<span class="fa fa-exclamation-circle fa-2x" '
                            +    'style="vertical-align:middle;color:#e10000;">'
                            + '</span> Alerta!', 'Tiene una alarma Activa! Por favor revisar Delivery', function(){ 
                              audio.pause();
                            });
            }

        }).fail(function(result){})
    }

    $('#nav-court').click(function(){
      alertify.confirm('Confirmar Corte de depositos', '¿Está seguro que desea realizar corte de manera Manual?', function(){ 
        $.ajax({
        url: "{{route('admin.court')}}", 
        type: "POST",
        }).done(function(data){
          alertify.success('Corte de depositos realizado correctamente!')
        }).fail(function(result){})
      }
      , function(){ });
    });
  </script>
@endif