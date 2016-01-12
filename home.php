
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

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="pull-right">
        <div class="form-group">  
          <div class="input-group">
            <input name="daterange" id="datarange" class="form-control" value="" readonly="" type="text">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <form id="form_reload" method="post" action="index.php">
              <input type="hidden" name="dataini" id="dataini_hidden">
              <input type="hidden" name="datafim" id="datafim_hidden">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row-fluid spacer">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Geral</h3>
        </div>
        <div class="panel-body" id="dados_gerais">

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

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Visitas por país<button class="btn btn-default btn-xs pull-right" onclick="drawRegionsMap();"><i class="fa fa-refresh"></i></button></h3>

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

  </div><!-- /.row-fluid -->
</div><!-- /.container -->

<script type="text/javascript">
  $(function() {
    var hoje = moment();
    var ontem = moment().subtract(1,'days');
    var dataini = null;
    var datafim = null;

    $('#datarange').daterangepicker({
      ranges: {
        'Hoje': [hoje, hoje],
        'Últimos 7 dias': [moment().subtract(7, 'days'), ontem],
        'Últimos 30 dias': [moment().subtract(30, 'days'), ontem],
        'Últimos 60 dias': [moment().subtract(60, 'days'), ontem],
        'Este Mês': [moment().startOf('month'), moment().endOf('month')],
        'Mês passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: moment().subtract(30, 'days'),
      endDate: ontem,
      "autoApply": true,
      "maxDate": hoje,
    })//show datepicker when clicking on the icon
      .next().click(function(){
      $(this).prev().focus();
    });

    $('#datarange').change(function(){
      var value = $(this).val();
      var result = value.split(" - ");    
      var char = "/";
      dataini = result[0].replace(eval("/"+char+"/g"), "red"); 
      dataini = moment(dataini, "DD MM YYYY");

      datafim = result[1].replace(eval("/"+char+"/g"), "red"); 
      datafim = moment(datafim, "DD MM YYYY");

      dataini = dataini.format("YYYY-MM-DD");
      datafim = datafim.format("YYYY-MM-DD");

      $("#dataini_hidden").val(dataini);
      $("#datafim_hidden").val(datafim);
      $("#form_reload").submit();
    });

  });
</script>
<?php
if(isset($_REQUEST['dataini'])){
  $dataini = $_REQUEST['dataini'];
}else{
  $dataini = date('Y-m-d', strtotime('-30 day'));
}
if(isset($_REQUEST['datafim'])){
  $datafim = $_REQUEST['datafim'];
}else{
  $datafim = date('Y-m-d', strtotime('-1 day'));
}

require 'gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");
$ga->requestAccountData();

foreach($ga->getAccounts() as $result)
{
  //echo $result . ' ' . $result->getId() . ' (' . $result->getProfileId() . ")<br />";
}
$id = $result->getProfileId();
//$id = 114290063;
$ga->requestReportData($id, null, array('pageviews', 'visits', 'users', 'percentNewSessions', 'bounceRate', 'avgSessionDuration','sessions'), null, null, $dataini, $datafim);
foreach ($ga->getResults() as $dadosGlobais) { 
  $pageviews_global = $dadosGlobais->getPageViews();
  $visits_global = $dadosGlobais->getVisits();
  $users_global = $dadosGlobais->getUsers();
  $percentNewSessions_global = $dadosGlobais->getPercentNewSessions();
  $bounceRate_global = $dadosGlobais->getBounceRate();
  $avgSessionDuration_global = $dadosGlobais->getAvgSessionDuration();
  $sessions_global = $dadosGlobais->getSessions();  
}
?>
<script type="text/javascript">
  $(function(){
    var id = <?=$id?>;
    var dataini = "<?=$dataini?>";
    var datafim = "<?=$datafim?>";
    $.ajax({
      method: "POST",
      url: "ajax_dadosglobais.php",
      data: { id:id, dataini: dataini, datafim: datafim },
      dataType: "json"
    })
    .done(function( response ) {
      var html = "";
      html += "<p>Sessões: <strong>"+response.sessions_global+"</strong></p>";
      html += "<p>Usuários: <strong>"+response.users_global+"</strong></p>";
      html += "<p>Visualizções de Páginas: <strong>"+response.pageviews_global+"</strong></p>";
      html += "<p>Páginas/sessão: <strong>"+response.visits_global+"</strong></p>";
      html += "<p>Duração média da sessão: <strong>"+response.avgSessionDuration_global+"</strong></p>";
      html += "<p>Taxa de rejeição: <strong>"+response.bounceRate_global+"%</strong></p>";
      html += "<p>Porcentagem de novas sessões: <strong>"+response.percentNewSessions_global+"%</strong></p>";

      $("#dados_gerais").html(html);
    });
  });
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
<script type="text/javascript"> 
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);

  function drawChart() {
    <?php
      $ga->requestReportData($id,null,array('percentNewSessions', 'sessionsPerUser'), null, null, $dataini, $datafim);
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
      $ga->requestReportData($id, array('country','countryIsoCode'), array('visits'), null, null, $dataini, $datafim);
      $array = "['País', 'Visitas'],";
      foreach ($ga->getResults() as $dados) { 
        $array .= "['".$dados->getCountry()."', ".$dados->getVisits()."],";
      }    
    ?>
    var data = google.visualization.arrayToDataTable([<?=$array?>]);

    var options = {backgroundColor: '#81d4fa',defaultColor: '#f5f5f5', height: '300', colorAxis: {colors: ['#90EE90', '#006400']}};

    var chartCountry = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));
    var chartState = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));

    google.visualization.events.addListener(chartCountry, 'regionClick', function(e) {
      var country = e.region;
      var filter = "countryIsoCode == "+country;
      var id = <?=$id?>;
      var dataini = "<?=$dataini?>";
      var datafim = "<?=$datafim?>";
      $.ajax({
        method: "POST",
        url: "ajax_estado.php",
        data: { id:id, filter: filter, dataini: dataini, datafim: datafim }
      })
      .done(function( response ) {
        var data = google.visualization.arrayToDataTable( eval('[' + response + ']'));
        var opts = {
          region: country,
          displayMode: 'regions',
          resolution: 'provinces',
          height: 300
        };
        chartCountry.clearChart();
        chartState.draw(data, opts);
      });
    });
    chartState.clearChart();
    chartCountry.draw(data, options);
  }
</script>
<script type="text/javascript">
  google.load('visualization', "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    <?php
      $ga->requestReportData($id, 'language', array('visits', 'percentNewSessions', 'newUsers', 'sessions'), null, null, $dataini, $datafim);
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
      $ga->requestReportData($id, array('operatingSystem','browser',), array('sessions'), null, null, $dataini, $datafim);
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
      $ga->requestReportData($id, array('deviceCategory'), 'sessions', null, null, $dataini, $datafim);
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
      $ga->requestReportData($id, array('source'), 'sessions', null, null, $dataini, $datafim);
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
      $ga->requestReportData($id, array('city'), 'sessions', '-sessions', null, $dataini, $datafim, 1, 12);
      foreach ($ga->getResults() as $dados) {
        $porcentagem = ($dados->getSessions() / $sessions_global) * 100;
        $array .= "['".$dados."', '".$dados->getSessions()."','".substr($porcentagem,0,4)."%'],";

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