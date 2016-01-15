<?php
require 'gapi.class.php';
require 'config.php';
$ga = new gapi($my_email, $my_key);

$id = $_POST['id'];
$dataini = $_POST['dataini'];
$datafim = $_POST['datafim'];

$ga->requestReportData($id, null, array('pageviews', 'visits', 'users', 'percentNewSessions', 'bounceRate', 'avgSessionDuration','sessions'), null, null, $dataini, $datafim);

$status = false;

foreach ($ga->getResults() as $dadosGlobais) { 
  $pageviews_global = $dadosGlobais->getPageViews();
  $visits_global = $dadosGlobais->getVisits();
  $users_global = $dadosGlobais->getUsers();
  $percentNewSessions_global = substr($dadosGlobais->getPercentNewSessions(),0,4);
  $bounceRate_global = substr($dadosGlobais->getBounceRate(),0,4);
  $avgSessionDuration_global = $dadosGlobais->getAvgSessionDuration();
  $sessions_global = $dadosGlobais->getSessions(); 
  
  $status = true;
}

if($status){
  $retorno = array('pageviews_global' => $pageviews_global, 'visits_global' => $visits_global, 'users_global' => $users_global, 'percentNewSessions_global' => $percentNewSessions_global, 'bounceRate_global' => $bounceRate_global, 'avgSessionDuration_global' => $avgSessionDuration_global, 'sessions_global' => $sessions_global, 'status' => $status);
}else{
  $retorno = NULL;
}


echo json_encode($retorno);  