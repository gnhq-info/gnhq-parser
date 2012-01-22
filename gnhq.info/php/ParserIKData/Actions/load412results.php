<?php
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

foreach ($tikLinks as $tik => $link) {
    var_dump($tik);
    var_dump($link);
    $table = $processor->getResultTable($link);
    $cells = $processor->getResultsFromTable($table);
    var_dump($cells);
    die();
}