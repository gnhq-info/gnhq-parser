<?php
include_once 'include.php';
$electionCode = '412';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Watch412();
$protoGateway = new ParserIKData_Gateway_Protocol412();

$watchGn = ParserIKData_Model_Protocol412::TYPE_GN;

foreach (ParserIKData_Model_Okrug::getAllOBjects() as $okrug) {
    $okrugAbbr = $okrug->getAbbr();

    $count = $gateway->getCount($watchGn, $okrugAbbr, true);
    print $okrugAbbr . '   '. $count . PHP_EOL;
}