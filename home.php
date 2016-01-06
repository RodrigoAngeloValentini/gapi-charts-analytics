<?php
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
    
    var options = {backgroundColor: '#81d4fa',defaultColor: '#f5f5f5'};
    
    var chart = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));
    chart.draw(data, options);
  }
</script>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Starter Template for Bootstrap</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>
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
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Geral</h3>
            </div>
            <div class="panel-body">
              <?php  
              $ga->requestReportData($id, null, array('pageviews', 'visits', 'users', 'percentNewSessions', 'bounceRate', 'avgSessionDuration'));
              foreach ($ga->getResults() as $dados) { }      
              ?>
              <p>Sessões: <strong><?=$dados->getVisits()?></strong></p>
              <p>Usuários: <strong><?=$dados->getUsers()?></strong></p>
              <p>Visualizções de Páginas: <strong><?=$dados->getPageviews()?></strong></p>
              <p>Páginas/sessão: <strong><?=$dados->getVisits()?></strong></p>
              <p>Duração média da sessão: <strong><?=$dados->getAvgSessionDuration()?></strong></p>
              <p>Taxa de rejeição: <strong><?=$dados->getBounceRate()?>%</strong></p>
              <p>Porcentagem de novas sessões: <strong><?=substr($dados->getPercentNewSessions(), 0, 5)?>%</strong></p>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Usuários</h3>
            </div>
            <div class="panel-body">
              <div id="chart_div_user"></div>  
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Visitas por país</h3>
            </div>
            <div class="panel-body">
              <div id="chart_div_geo" class="center-block"></div>  
            </div>
          </div>
        </div>
      </div><!-- /.row-fluid -->
    </div><!-- /.container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>
