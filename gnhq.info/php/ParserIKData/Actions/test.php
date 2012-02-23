<?php
include_once 'include.php';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();

foreach (ParserIKData_Model_Okrug::getAllOBjects() as $okrug) {
    var_dump($okrug->getParams());
}