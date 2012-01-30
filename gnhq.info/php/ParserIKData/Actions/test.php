<?php
include_once 'include.php';
$electionCode = '412';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse
    ->loadAllOkrugs()
    ->loadAllTiks()
    ->loadAllUiks()
    ->loadElectionResults($electionCode, ParserIKData_Model_Protocol412::TYPE_GN)
    ->loadElectionResults($electionCode, ParserIKData_Model_Protocol412::TYPE_OF);

$myuik = ParserIKData_Model_UIK::getFromPool('139');
/* @var $myuik ParserIKData_Model_UIK */
$ofResult = $myuik->getElection412Result(ParserIKData_Model_Protocol412::TYPE_OF);
$gnResult = $myuik->getElection412Result(ParserIKData_Model_Protocol412::TYPE_GN);
$ofResultData = $ofResult->getPartyResults();
$gnResultData = $gnResult->getPartyResults();
var_dump($ofResultData);
var_dump($gnResultData);