<?php
include_once 'include.php';


/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

$processor = new ParserIKData_Site_Mosgor();

// создание объектов по первой иерерхии
$processor->initModelsByHierarchy();

// получение ссылок по вторичной иерархии
$processor->loadTikDataForOkrugs();
$processor->loadTikAddressAndSostavLinks();

// загрузка информации
$processor->loadTikData();

// сохранение данных
$warehouse->saveAllOkrugs();//->saveAllTiks();

print PHP_EOL . 'Данные успешно загружены' . PHP_EOL;
exit(0);