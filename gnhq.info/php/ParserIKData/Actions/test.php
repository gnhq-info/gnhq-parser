<?php
include_once 'include.php';
$electionCode = '412';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Protocol412();
$cao = $gateway->getOfficialResultForOkrug('ЦАО');
print_r($cao->getDiagramData(true, 2));
$u139 = $gateway->getOfficialResultForUik(139);
print_r($u139->getDiagramData(true, 2));
$moscow = $gateway->getOfficialResultForMoscow();
print_r($moscow->getDiagramData(true, 2));