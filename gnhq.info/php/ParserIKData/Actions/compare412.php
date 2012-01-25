<?php
include_once 'include.php';

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$warehouse
    ->loadElectionResults('412', ParserIKData_Model_Protocol412::TYPE_GN)
    ->loadElectionResults('412', ParserIKData_Model_Protocol412::TYPE_OF)
;
print (count(ParserIKData_Model_Protocol412::getAllOBjects()));