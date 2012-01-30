<?php
include_once 'include.php';

/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$processor = new ParserIKData_Site_Gn412();
$processor->getReportList();

foreach(ParserIKData_Model_Report412::getAllOBjects() as $report) {
    /* @var $report ParserIKData_Model_Report412 */
    $processor->loadContent($report);
}

$warehouse->saveElectionReports('412');