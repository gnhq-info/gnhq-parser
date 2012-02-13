<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';


$warehouse->loadAllOkrugs();
$okrugGateway = new ParserIKData_Gateway_Okrug();


$view = new stdClass();
$view->okrugs = ParserIKData_Model_Okrug::getAllOBjects();
$okrugDiscr = $okrugGateway->getDiscrepancyCount();


foreach ($okrugDiscr as $okrugAbbr => $count) {
    ParserIKData_Model_Okrug::getFromPool($okrugAbbr)->setDiscrepancyCount($count);
}


$view->diagRows = array(
	array('key' => 'S',  'hdr' => 'СР',               'title' => 'Справедливая Россия', 'color' => 'yellow'),
	array('key' => 'L',  'hdr' => 'ЛДПР',             'title' => 'Либерально-Демократическая Партия России', 'color' => 'orange'),
	array('key' => 'PR', 'hdr' => 'ПР',               'title' => 'Патриоты России', 'color' => '#ff77ff'),
	array('key' => 'K',  'hdr' => 'КПРФ',             'title' => 'Коммунистичесая Партия Российской Федерации', 'color' => 'red'),
	array('key' => 'Y',  'hdr' => 'Яблоко',           'title' => 'Яблоко', 'color' => '#66ee00'),
	array('key' => 'E',  'hdr' => 'ЕР',               'title' => 'Единая Россия', 'color' => '#004466'),
	array('key' => 'PD', 'hdr' => 'ПД',               'title' => 'Правое Дело', 'color' => '#66ffff'),
	array('key' => 'AT', 'hdr' => 'Явка',             'title' => 'Явка', 'color' => 'magenta'),
	array('key' => 'SP', 'hdr' => 'Недействительные', 'title' => 'Недействительные', 'color' => 'grey'),
);

include 'tpl/index.tpl';