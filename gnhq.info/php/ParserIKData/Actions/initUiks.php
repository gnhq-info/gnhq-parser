<?php
include_once 'include.php';

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = new ParserIKData_Warehouse_Csv();

$warehouse->loadAllTiks();

