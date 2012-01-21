<?php
set_time_limit(0);
include_once 'include.php';

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = new ParserIKData_Warehouse_Csv();
$warehouse->loadAllOkrugs()->loadAllTiks();

$processor = new ParserIKData_SiteProcessor();

foreach (ParserIKData_Model_TIK::getAllOBjects() as $tik) {
    $processor->createTikUiks($tik);
}
$warehouse->saveAllUiks();