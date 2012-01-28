<?php
include_once 'include.php';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$warehouse->loadAllOkrugs();
$warehouse->loadAllTiks();
$warehouse->loadAllUiks();
$myuik = ParserIKData_Model_UIK::getFromPool('139');
var_dump($myuik);