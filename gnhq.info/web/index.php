<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';


$warehouse->loadAllOkrugs();

$watchGateway = new ParserIKData_Gateway_Watch412();

$view = new stdClass();
$view->okrugs = ParserIKData_Model_Okrug::getAllOBjects();
$view->totalUikCount = $watchGateway->getCount(WATCH_GN);
$view->discrepancyUikCount = $watchGateway->getCount(WATCH_GN, null, true);


include 'tpl/index.tpl';