<?php
// loading official results from cik site
include_once 'include.php';

/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$processor = new ParserIKData_Site_Res412();
$okrugLinks = $processor->getOkrugLinks();

$tikLinks = array();

foreach ($okrugLinks as $okrug => $link) {
    $tikLinks = array_merge($tikLinks, $processor->getTIKLinks($link));
}

foreach ($tikLinks as $tikName => $link) {
    $table = $processor->getResultTable($link);
    $data = $processor->getResultsFromTable($table);
    foreach ($data as $uikName => $uikData) {
        $processor->createResult($uikName, $uikData);
    }
    print_r($tikName . ' processed' . PHP_EOL . str_repeat('-', 20) . PHP_EOL);
}

$warehouse->saveElectionResults('412', ParserIKData_Model_Protocol::TYPE_OF);
print ('official results loaded');
