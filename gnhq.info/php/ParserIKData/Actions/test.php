<?php
include_once 'include.php';
$electionCode = '412';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
//$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Protocol412();
$okrGateway = new ParserIKData_Gateway_Okrug();

var_dump($okrGateway->getDiscrepancyCount());


function _debugPringDiagram($diagData)
{
    foreach ($diagData as $k => $v) {
        print _debugFormatKey($k) . _debugFormatValue($v) . '     ';
    }
    print array_sum($diagData) - $diagData['AT'];
    print PHP_EOL . str_repeat('-', 20) . PHP_EOL;
}

function _debugFormatKey($key)
{
    return str_pad($key, 3, ' ', STR_PAD_RIGHT);
}

function _debugFormatValue($val)
{
    return str_pad($val, 5, ' ', STR_PAD_LEFT);
}