<?php
include_once 'include.php';

$electionCode = '412';
$resultType = 'GN';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse
    ->loadAllOkrugs()
    ->loadAllOkrugs()
    ->loadAllUiks()
    ->loadElectionResults($electionCode, 'GN')
    ->loadElectionResults($electionCode, 'OF');

