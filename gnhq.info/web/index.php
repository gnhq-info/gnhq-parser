<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';


$warehouse->loadAllOkrugs();

$watchGateway = new ParserIKData_Gateway_Watch412();

$view = new stdClass();
$view->okrugs = ParserIKData_Model_Okrug::getAllOBjects();
$view->totalUikCount = $watchGateway->getCount(WATCH_GN);
$view->discrepancyUikCount = $watchGateway->getCount(WATCH_GN, null, true);

$view->diagRows = array(
	array('key' => 'S',  'hdr' => 'СР', 'color' => 'yellow'),
	array('key' => 'L',  'hdr' => 'ЛДПР', 'color' => 'orange'),
	array('key' => 'PR', 'hdr' => 'ПР', 'color' => '#ff77ff'),
	array('key' => 'K',  'hdr' => 'КПРФ', 'color' => 'red'),
	array('key' => 'Y',  'hdr' => 'Яблоко', 'color' => '#66ee00'),
	array('key' => 'E',  'hdr' => 'ЕР', 'color' => '#004466'),
	array('key' => 'PD', 'hdr' => 'ПД', 'color' => '#66ffff'),
	array('key' => 'AT', 'hdr' => 'Явка', 'color' => 'magenta'),
	array('key' => 'SP', 'hdr' => 'Недействительные', 'color' => 'grey'),
);

include 'tpl/index.tpl';