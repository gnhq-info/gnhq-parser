<?php
include_once 'include.php';

/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$processor = new ParserIKData_Site_Res412();
$okrugLinks = $processor->getOkrugLinks();

var_dump($okrugLinks);

foreach ($okrugLinks as $okrug => $link) {
    print_r($okrug . PHP_EOL);
    var_dump($processor->getTIKLinks($link));
    print_r(PHP_EOL . '---done---' . PHP_EOL);
}