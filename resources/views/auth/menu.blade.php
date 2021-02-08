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
      <div class="sidebar-wrapper">
        <ul class="nav">
          @if(Auth::guard('admin')->check())
            <li class="nav-item" id="nav-dashboard">
              <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="material-icons">dashboard</i>
                <p>Inicio</p>
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
          <li class="nav-item active-pro">
            <a class="nav-link" href="{{route('logout')}}">
            <i class="material-icons">login</i>
              <p>Salir</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
@if(Auth::guard('admin')->check())
  <script>
  window.Echo.channel('channel-ctpagaAdmin').listen('.event-ctpagaAdmin', (data) => {
    
    var audio = new Audio("{{asset('sounds/alarma.mp3')}}"); 
    audio.play();
    audio.loop=true; 

    alertify.alert('<span class="fa fa-exclamation-circle fa-2x" '
                  +    'style="vertical-align:middle;color:#e10000;">'
                  + '</span> Alerta!', 'Tiene una alarma Activa! Por favor revisar Delivery', function(){ 
                    audio.pause();
                   });

  });
  </script>
@endif