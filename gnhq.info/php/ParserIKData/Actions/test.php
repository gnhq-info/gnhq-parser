<?php
include_once 'include.php';
$electionCode = '412';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
//$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Watch412();
$protoGateway = new ParserIKData_Gateway_Protocol412();



$okrugAbbr = 'ЗелАО';
$watchGn = ParserIKData_Model_Protocol412::TYPE_GN;

var_dump($protoGateway->getCondDiscrepancy($watchGn, null, ParserIKData_Model_Protocol412::getPartyIndices())); die();

$count = $gateway->getCount($watchGn, $okrugAbbr);
var_dump($count);