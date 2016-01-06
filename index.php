<?php
require 'gapi-google-analytics-php-interface/gapi.class.php';
$ga = new gapi("rodrigo-angelo-valentini@massive-team-112917.iam.gserviceaccount.com", "key.p12");
$ga->requestAccountData();

foreach($ga->getAccounts() as $result)
{
  //echo $result . ' ' . $result->getId() . ' (' . $result->getProfileId() . ")<br />";
}
$id = $result->getProfileId();
//$id = 114290063;

//echo '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
include 'home.php';
?>


