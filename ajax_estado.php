<?php
require 'gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");

$id = $_POST['id'];
$filter = $_POST['filter'];
$dataini = $_POST['dataini'];
$datafim = $_POST['datafim'];

$ga->requestReportData($id, array('regionIsoCode'), array('visits'),'-visits',$filter, $dataini, $datafim);
$array = "['Estado', 'Visitas']";

foreach ($ga->getResults() as $dados) {
  $array .= ",['".$dados."', ".$dados->getVisits()."]";
}
echo $array;  