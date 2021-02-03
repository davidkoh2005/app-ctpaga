  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard/google-css.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard/font-awesome.min') }}">
  <!-- CSS Files -->
  <link href="{{ asset('css/dashboard/material-dashboard.css?v=2.1.2') }}" rel="stylesheet" />
  <script src="{{ asset('js/dashboard/script.js') }}" type="text/javascript"></script>
  <script>
    window.Echo.channel('channel-ctpagaAdmin').listen('.event-ctpagaAdmin', (data) => {
      
      alertify.alert('<span class="fa fa-exclamation-circle fa-2x" '
                    +    'style="vertical-align:middle;color:#e10000;">'
                    + '</span> Alerta!', 'Tiene una alarma Activa! Por favor revisar Delivery', function(){  });

    });
  </script>