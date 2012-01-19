<?php
include_once 'include.php';

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = new ParserIKData_Warehouse_Csv();

$processor = new ParserIKData_SiteProcessor();

// создание объектов по первой иерерхии
$processor->initModelsByHierarchy();

// получение ссылок по вторичной иерархии
$processor->loadTikDataForOkrugs();
$processor->loadTikAddressAndSostavLinks();

// загрузка информации
$processor->loadTikData();

// сохранение данных
$warehouse->saveAllOkrugs()->saveAllTiks();