<?php
include_once 'include.php';

$electionCode = '412';
$resultType = 'GN';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadElectionResults($electionCode, 'GN')->loadElectionResults($electionCode, 'OF');

$of139 = ParserIKData_Model_Protocol412::getFromPool('139:UIK:OF');
$gn139 = ParserIKData_Model_Protocol412::getFromPool('139:UIK:GN');
var_dump($of139);
var_dump($gn139);