<?php
require 'gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");
$ga->requestAccountData();

foreach($ga->getAccounts() as $result)
{
  //echo $result . ' ' . $result->getId() . ' (' . $result->getProfileId() . ")<br />";
}
$id = $result->getProfileId();
//$id = 114290063;
$ga->requestReportData($id, null, array('pageviews', 'visits', 'users', 'percentNewSessions', 'bounceRate', 'avgSessionDuration','sessions'));
foreach ($ga->getResults() as $dadosGlobais) { 
  $pageviews_global = $dadosGlobais->getPageViews();
  $visits_global = $dadosGlobais->getVisits();
  $users_global = $dadosGlobais->getUsers();
  $percentNewSessions_global = $dadosGlobais->getPercentNewSessions();
  $bounceRate_global = $dadosGlobais->getBounceRate();
  $avgSessionDuration_global = $dadosGlobais->getAvgSessionDuration();
  $sessions_global = $dadosGlobais->getSessions();  
}
//echo '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
//$inicio = date('Y-m-01', strtotime('-1 month')); // 1° dia do mês passado
//$fim = date('Y-m-t', strtotime('-1 month')); // Último dia do mês passado
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
<script type="text/javascript"> 
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);

  function drawChart() {
    <?php
    $ga->requestReportData($id,null,array('percentNewSessions', 'sessionsPerUser'));
    foreach($ga->getResults() as $result){
      $percentNewSessions = $result->getPercentNewSessions();
      $percentReturning = 100 - $percentNewSessions;
    }
    $array = "['UserType', 'Porcentagem'],";
    $array .= "['New Visitor', ".$percentNewSessions."],";
    $array .= "['Returning Visitor', ".$percentReturning."],";
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);
    var options = { is3D: true, legend: {position: 'top', alignment: 'center'}, height: 250, witdh: 350};

    var chart = new google.visualization.PieChart(document.getElementById('chart_div_user'));
    chart.draw(data, options);
  }
</script>
<script type="text/javascript">
  google.load('visualization', "1", {'packages':['geochart']});
  google.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
    <?php
  $ga->requestReportData($id, 'country', array('visits'));
      $array = "['País', 'Visitas'],";
      foreach ($ga->getResults() as $dados) { 
        $array .= "['".$dados."', ".$dados->getVisits()."],";
      }    
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);

    var options = {backgroundColor: '#81d4fa',defaultColor: '#f5f5f5', height: '400', colorAxis: {colors: ['#90EE90', '#006400']}};

    var chartCountry = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));
    var chartState = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));
    var chartCity = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));

    google.visualization.events.addListener(chartCountry, 'regionClick', function(e) {
      var country = e.region;
      <?php
      $ga->requestReportData($id, 'region', array('visits'),'-visits');
      $array = "['Estado', 'Visitas'],";
      foreach ($ga->getResults() as $dados) { 
        $array .= "['".$dados."', ".$dados->getVisits()."],";
      }    
      ?>
      var data = google.visualization.arrayToDataTable([<?=$array?>]);
      var opts = {
        region: country,
        displayMode: 'regions',
        resolution: 'provinces',
        height: 400
      };
      chartCountry.clearChart();
      chartState.draw(data, opts);
    });
    
    google.visualization.events.addListener(chartState, 'regionClick', function(e) {
      var state = e.region;
      <?php
      $ga->requestReportData($id, 'city', array('visits'),'-visits');
      $array = "['Cidade', 'Visitas'],";
      foreach ($ga->getResults() as $dados) { 
        $array .= "['".$dados."', ".$dados->getVisits()."],";
      }    
      ?>
      var data = google.visualization.arrayToDataTable([<?=$array?>]);
      var opts = {
        region: state,
        displayMode: 'markers',
        resolution: 'provinces',
        height: 400
      };
      chartState.clearChart();
      chartCity.draw(data, opts);
    });

    chartCountry.draw(data, options);

  }
</script>
<script type="text/javascript">
  google.load('visualization', "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    <?php

  $ga->requestReportData($id, 'language', array('visits', 'percentNewSessions', 'newUsers', 'sessions'));
        $array = "['Idioma', 'Visitas', { role: 'annotation' }], ";
        foreach ($ga->getResults() as $dados) {
          $porcentagem = $dados->getSessions()/$sessions_global*100;
          $array .= "['".$dados."', ".$dados->getVisits().", '".substr($porcentagem,0,4)."%'],";
        }      
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);

    var view = new google.visualization.DataView(data);

    var options = {
      width: 400,
      height: 300,
      bar: {groupWidth: "75%"},
      legend: { position: "none" },
    };
    var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
    chart.draw(view, options);
  }
</script>
<script type="text/javascript">
  google.load('visualization', "1", {'packages':['bar']});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    <?php
  $ga->requestReportData($id, array('operatingSystem','browser',), array('sessions'));
      $arr = []; 
      foreach ($ga->getResults() as $dados) {
        $arr [$dados->getOperatingSystem()][$dados->getBrowser()] = $dados->getSessions();
        $arrBrowser[] = $dados->getBrowser();
        $arrSystem[] = $dados->getOperatingSystem();
      }
      $arrBrowser = array_unique($arrBrowser);
      $arrSystem = array_unique($arrSystem);

      $arrayTopo = "['Sistema'";
      foreach ($arrBrowser as $browser){
        $arrayTopo .= ",'".$browser."'";
      }
      $arrayTopo .= "]";
      $arrayConteudo = "";
      foreach ($arrSystem as $system){
        $arrayConteudo .= ",['".$system."'";
        foreach ($arrBrowser as $browser){
          if(array_key_exists($browser, $arr[$system])){
            $view = $arr[$system][$browser];  
          }else{
            $view = NULL;
          }

          $arrayConteudo .=  ",".intval($view)."";
        }
        $arrayConteudo .= "]";
      }
    ?>
    var data = google.visualization.arrayToDataTable([<?=$arrayTopo.$arrayConteudo?>]);

    var options = {
      width: 300,
      height: 300,
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, options);
  }
</script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    <?php
  $ga->requestReportData($id, array('deviceCategory'), 'sessions');
      $array = "['Dispositivo','Porcentagem']";
      foreach ($ga->getResults() as $dados) {
        $porcentagem = ($dados->getSessions() / $sessions_global) * 100;
        $array .= ",['".$dados."',".substr($porcentagem,0,4)."]";
      }
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);

    var options = {
      pieHole: 0.2,
      width: 500,
      height: 300
      //is3D: true
    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);
  }
</script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:['corechart']});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    <?php
  $ga->requestReportData($id, array('source'), 'sessions');
      $array = "['Origem','Sessões',{ role: 'annotation' }]";
      foreach ($ga->getResults() as $dados) {
        $array .= ",['".$dados."',".$dados->getSessions().",'".substr($dados->getSessions()/$sessions_global*100,0,4)."%']";    
      }
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);

    var view = new google.visualization.DataView(data);

    var options = {
      width: 500,
      height: 300,
      bar: {groupWidth: "65%"},
      legend: { position: "none" },
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
    chart.draw(view, options);
  }
</script>
<script type="text/javascript">
  google.load("visualization", "1.1", {packages:["table"]});
  google.setOnLoadCallback(drawTable);

  function drawTable() {
    <?php
  $array = "";
      $ga->requestReportData($id, array('city'), 'sessions', '-sessions', null, null, null, 1, 10);
      foreach ($ga->getResults() as $dados) {
        $porcentagem = ($dados->getSessions() / $sessions_global) * 100;
        $array .= "['".$dados."', '".$dados->getSessions()."','".substr($porcentagem,0,4)."%'],";
        //$array .= "['".$dados."', '".$dados->getSessions()."'],";
      }
    ?>
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Cidade');
    data.addColumn('string', 'Sessões');
    data.addColumn('string', 'Porcentagem Sessões');
    data.addRows([<?=$array?>]);


    var table = new google.visualization.Table(document.getElementById('table_div'));

    table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
  }
</script>

<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">GAPI.js + Google Chart.js</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">Home</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Geral</h3>
          </div>
          <div class="panel-body">
            <p>Sessões: <strong><?=$sessions_global?></strong></p>
            <p>Usuários: <strong><?=$users_global?></strong></p>
            <p>Visualizções de Páginas: <strong><?=$pageviews_global?></strong></p>
            <p>Páginas/sessão: <strong><?=$visits_global?></strong></p>
            <p>Duração média da sessão: <strong><?=$avgSessionDuration_global?></strong></p>
            <p>Taxa de rejeição: <strong><?=$bounceRate_global?>%</strong></p>
            <p>Porcentagem de novas sessões: <strong><?=substr($percentNewSessions_global, 0, 5)?>%</strong></p>
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Usuários</h3>
          </div>
          <div class="panel-body">
            <div id="chart_div_user"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Idioma</h3>
          </div>
          <div class="panel-body">
            <div id="barchart_values" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Navegador e Sistema Operacional</h3>
          </div>
          <div class="panel-body">
            <div id="columnchart_material" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Visitas por país</h3>
          </div>
          <div class="panel-body">
            <div id="chart_div_geo" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Dispositivo</h3>
          </div>
          <div class="panel-body">
            <div id="donutchart" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Origem</h3>
          </div>
          <div class="panel-body">
            <div id="columnchart_values" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Cidades</h3>
          </div>
          <div class="panel-body">
            <div id="table_div" class="center-block"></div>  
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Estados</h3>
          </div>
          <div class="panel-body">
            <?php
              $filter = "country == Brazil || country == United States";
              $ga->requestReportData($id, array('country','region'), array('visits'),'-visits',$filter);
              
              foreach ($ga->getResults() as $dados) { 
                echo $dados,'<br>';
              } 
            ?>
          </div>
        </div>
      </div>


    </div><!-- /.row-fluid -->
  </div><!-- /.container -->

