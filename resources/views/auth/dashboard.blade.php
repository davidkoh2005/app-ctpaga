<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/logo/logoctpaga.ico') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/logo/logoctpaga.ico') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>CTpaga</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  @include('bookshop')
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
                  <p class="card-category"> $ USD </p>
                  <h3 class="card-title">$ {{$totalShoppingUSD}}</h3>
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
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category"> $ USD (Por Confirmar)</p>
                  <h3 class="card-title">$ {{$totalPendingUSD}}</h3>
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
                  <p class="card-category"> Bs Venezuela </p>
                  <h3 class="card-title">Bs {{$totalShoppingBS}}</h3>
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
                <div class="card-header card-header-dark card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">account_balance</i>
                  </div>
                  <p class="card-category"> Bs Venezuela (Por Confirmar)</p>
                  <h3 class="card-title">Bs {{$totalPendingBS}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Hoy
                  </div>
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
                  <div class="ct-chart" id="dailySalesUSDChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas con $ USD </h4>
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
                  <div class="ct-chart" id="dailySalesBSChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas con Bs Venezuela </h4>
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
  @include('admin.bookshopBottom')
  
  <script>
    var statusMenu = "{{$statusMenu}}";
    var listDay=[];
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
    
    md.initDashboardPageCharts();

    function updateData()
    {

        var date=[];
        var dayTotalSales=[];
        var dayTotalUSD=[];
        var dayTotalBS=[];

        $.each(listDay, function(i, item) {
            date.push(item.dia);
            dayTotalSales.push(item.totalSales);
            dayTotalUSD.push(item.totalUSD);
            dayTotalBS.push(item.totalBS);
        });


        dataDailySalesChart = {
          labels: date,
          series: [
            dayTotalSales
          ]
        }; 

        dataDailySalesUSDChart = {
          labels: date,
          series: [
            dayTotalUSD
          ]
        };

        dataDailySalesBSChart = {
          labels: date,
          series: [
            dayTotalBS
          ]
        };


        var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);
        md.startAnimationForLineChart(dailySalesChart);

        var dailySalesUSDChart = new Chartist.Line('#dailySalesUSDChart', dataDailySalesUSDChart, optionsDailySalesUSDChart);
        md.startAnimationForLineChart(dailySalesUSDChart);

        var dailySalesBSChart = new Chartist.Line('#dailySalesBSChart', dataDailySalesBSChart, optionsDailySalesBSChart);
        md.startAnimationForLineChart(dailySalesBSChart);
    }
  </script>
</body>

</html>