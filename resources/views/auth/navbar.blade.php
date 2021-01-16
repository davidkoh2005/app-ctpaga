    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;" id="title-navbar"></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          @if(Auth::guard('web')->check())
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="row px-3"> 
                  @if($pictureUser)
                    <img class="mr-3 profile-pic" src="{{$pictureUser->url}}">
                  @else
                    <img class="mr-3 profile-pic" src="../images/perfil.png">
                  @endif
                  <div class="flex-column">
                        <h5 class="mb-0 profile-name">{{$commerceName}}</h5> 
                  </div>
                </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  @if(count($commercesUser)>1)
                    @foreach ($commercesUser as $commerceUser)   
                      @if($statusMenu == "dashboard")  
                        <a class="dropdown-item" href="{{route('commerce.dashboard', ['commerceId' => $commerceUser->id])}}">{{$commerceUser->name}}</a>
                      @else
                        <a class="dropdown-item" href="{{route('commerce.transactions', ['commerceId' => $commerceUser->id])}}">{{$commerceUser->name}}</a>
                      @endif
                    @endforeach
                  @endif
                </div>
              </li>
            </ul>
          </div>
          @endif
        </div>
      </nav>
      <!-- End Navbar -->