<?php
include_once 'include.php';


/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = $serviceLocator->getWarehouse();

$processor = new ParserIKData_Site_Mosgor();

// создание объектов по первой иерерхии
$processor->initModelsByHierarchy();

// получение ссылок по вторичной иерархии
$processor->loadTikDataForOkrugs();
$processor->loadTikAddressAndSostavLinks();

// загрузка информации
$processor->loadTikData();

// сохранение данных
$warehouse->saveAllOkrugs()->saveAllTiks();