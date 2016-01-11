<?php
require 'gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");

$id = $_POST['id'];
$filter = $_POST['filter'];

$ga->requestReportData($id, array('regionIsoCode'), array('visits'),'-visits',$filter);
$array = "['Estado', 'Visitas']";

foreach ($ga->getResults() as $dados) {
  $array .= ",['".$dados."', ".$dados->getVisits()."]";
}
echo $array;  