<?php
require 'gapi.class.php';
require 'config.php';
$ga = new gapi($my_email, $my_key);

$id = $_POST['id'];
$dataini = $_POST['dataini'];
$datafim = $_POST['datafim'];


$ga->requestReportData($id,'date',array('1dayUsers'), 'date', null, $dataini, $datafim);
$resultado = $ga->getResults();

$ga->requestReportData($id,'date',array('7dayUsers'), 'date', null, $dataini, $datafim);
$resultado2 = $ga->getResults();

$ga->requestReportData($id,'date',array('14dayUsers'), 'date', null, $dataini, $datafim);
$resultado3 = $ga->getResults();

$ga->requestReportData($id,'date',array('30dayUsers'), 'date', null, $dataini, $datafim);
$resultado4 = $ga->getResults();

$lista = array();

for($i=0;$i<count($resultado);$i++){
  $lista['usuariosAtivos'][] = array(
      'date'  => $resultado[$i]->getDate(),
      'users1Day' => $resultado[$i]->get1dayUsers(),
      'users7Day' => $resultado2[$i]->get7dayUsers(),
      'users14Day' => $resultado3[$i]->get14dayUsers(),
      'users30Day' => $resultado3[$i]->get14dayUsers()
  );
}

echo json_encode($lista); 

