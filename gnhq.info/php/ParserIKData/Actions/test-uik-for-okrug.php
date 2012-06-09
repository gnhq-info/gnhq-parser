<?php
include_once 'include.php';
$electionCode = '412';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
//$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_UIK();

$okrugAbbr = 'ЦАО';
$watchOf = null;
$watchGn = ParserIKData_Model_Protocol::TYPE_GN;

$uiks = $gateway->getForOkrug($okrugAbbr, $watchOf);
foreach ($uiks as $uik) {
    _debugPringUik($uik);
}

function _debugPringUik($uik)
{
    print str_pad($uik->getUniqueId(), 5, ' ', STR_PAD_LEFT) . PHP_EOL;
}