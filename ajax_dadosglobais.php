<?php
require 'gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");

$id = $_POST['id'];
$dataini = $_POST['dataini'];
$datafim = $_POST['datafim'];

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

$retorno = array('pageviews_global' => $pageviews_global, 'visits_global' => $visits_global, 'users_global' => $users_global, 'percentNewSessions_global' => $percentNewSessions_global, 'bounceRate_global' => $bounceRate_global, 'avgSessionDuration_global' => $avgSessionDuration_global, 'sessions_global' => $sessions_global);

echo json_encode($retorno);  