<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../../../images/logo/logoctpaga.ico">
  <link rel="icon" type="image/png" href=".../../../images/logo/logoctpaga.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Ctpaga</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <script src="../../../js/bookshop/jquery.js"></script>
  <script src="../../../js/bookshop/jquery.min.js"></script>
  @include('admin.bookshop')
</head>

<body class="">
  @include('auth.menu')
    <div class="main-panel">
      @include('auth.navbar')
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">shopping_cart</i>
                  </div>
                  <p class="card-category">Total de Ventas</p>
                  <h3 class="card-title">{{$totalShopping}}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> Hoy
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category">@if(Auth::guard('admin')->check()) Stripe @else $ USD @endif</p>
                  <h3 class="card-title">$ {{$totalShoppingStripe}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Hoy
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category">@if(Auth::guard('admin')->check()) E-sitef @else Bs Venezuela @endif</p>
                  <h3 class="card-title">Bs {{$totalShoppingSitef}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Hoy
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-warning">
                  <div class="ct-chart" id="dailySalesChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas Diarias</h4>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> Ultimos 6 Dias
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-info">
                  <div class="ct-chart" id="dailySalesStripeChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas con Stripe</h4>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> Ultimos 6 Dias
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-success">
                  <div class="ct-chart" id="dailySalesSitefChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas con E-sitef</h4>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> Ultimos 6 Dias
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../../js/dashboard/core/jquery.min.js"></script>
  @include('admin.bookshopBottom')
  <script>
    var statusMenu = "{{$statusMenu}}";
    var listDay;
    $.ajax({
        url: "{{route('admin.dataGraphic')}}", 
        data: {"commerce_id" : "{{$idCommerce}}"},
        type: "POST",
        dataType: 'json',
    }).done(function(data){
      listDay = data;
      updateData();
    }).fail(function(result){
      alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
    });
    $(document).ready(function() {

      md.initDashboardPageCharts();
    });

    function updateData()
    {

      var date=[];
        var dayTotalSales=[];
        var dayTotalStripe=[];
        var dayTotalSitef=[];

        $.each(listDay, function(i, item) {
            date.push(item.dia);
            dayTotalSales.push(item.totalSales);
            dayTotalStripe.push(item.totalStripe);
            dayTotalSitef.push(item.totalSitef);
        });


        dataDailySalesChart = {
          labels: date,
          series: [
            dayTotalSales
          ]
        }; 

        dataDailySalesStripeChart = {
          labels: date,
          series: [
            dayTotalStripe
          ]
        };

        dataDailySalesSitefChart = {
          labels: date,
          series: [
            dayTotalSitef
          ]
        };

        optionsDaily= {
          lineSmooth: Chartist.Interpolation.cardinal({
            tension: 0
          }),
          chartPadding: {
            top: 20,
            right: 0,
            bottom: 0,
            left: 0
          },
          axisY: {
            onlyInteger: true,
          },
        }

        var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);
        md.startAnimationForLineChart(dailySalesChart);

        var dailySalesStripeChart = new Chartist.Line('#dailySalesStripeChart', dataDailySalesStripeChart, optionsDailySalesStripeChart);
        md.startAnimationForLineChart(dailySalesStripeChart);

        var dailySalesSitefChart = new Chartist.Line('#dailySalesSitefChart', dataDailySalesSitefChart, optionsDailySalesSitefChart);
        md.startAnimationForLineChart(dailySalesSitefChart);
    }
  </script>
</body>

</html>