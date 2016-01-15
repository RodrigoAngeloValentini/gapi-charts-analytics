<?php
require 'gapi.class.php';
require 'config.php';
$ga = new gapi($my_email, $my_key);

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