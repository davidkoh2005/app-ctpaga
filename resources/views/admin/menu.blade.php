  <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" data-image="../images/logo/logo.png">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="{{route('admin.dashboard')}}" class="simple-text logo-normal">
        <img src="../../images/logo/logo.png" alt="image" width="160px" height="60px">
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item" id="nav-dashboard">
            <a class="nav-link" href="{{route('admin.dashboard')}}">
              <i class="material-icons">dashboard</i>
              <p>Inicio</p>
            </a>
          </li>
          <li class="nav-item" id="nav-balance">
            <a class="nav-link" href="{{route('admin.balance')}}">
              <i class="material-icons">account_balance</i>
              <p>Depositos</p>
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
          <li class="nav-item active-pro">
            <a class="nav-link" href="{{route('admin.logout')}}">
            <i class="material-icons">login</i>
              <p>Salir</p>
            </a>
          </li>
        </ul>
      </div>
    </div>