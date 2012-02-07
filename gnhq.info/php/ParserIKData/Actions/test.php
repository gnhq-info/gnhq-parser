<?php
include_once 'include.php';
$electionCode = '412';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Protocol412();
$protocol = $gateway->getOfficialResultForOkrug('ЦАО');
var_dump($protocol->getDiagramData(true, 2));
