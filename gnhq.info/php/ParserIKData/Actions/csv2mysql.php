<?php
include_once 'include.php';

$electionCode = '412';
$resultType = 'OF';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouseCsv = new ParserIKData_Warehouse_Csv();
$warehouseCsv
    ->loadAllOkrugs()
    ->loadAllTiks()
    ->loadAllUiks()
    ->loadElectionResults($electionCode, 'GN')
    ->loadElectionResults($electionCode, 'OF');

$warehouse
    ->saveAllOkrugs()
    ->saveAllTiks()
    ->saveAllUiks()
    ->saveElectionResults($electionCode, 'GN')
    ->saveElectionResults($electionCode, 'OF');