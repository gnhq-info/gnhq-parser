<?php
include_once 'include.php';

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = $serviceLocator->getWarehouse();
$warehouse->loadAllOkrugs()->loadAllTiks();

$processor = new ParserIKData_Site_Mosgor();

foreach (ParserIKData_Model_TIK::getAllOBjects() as $tik) {
    $processor->createTikUiks($tik);
}
$warehouse->saveAllUiks();