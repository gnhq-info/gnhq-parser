<?php
if (!defined('PROJECT_STARTED')) {
    die();
}

ini_set('register_globals', 0);

$dirParts = explode(DIRECTORY_SEPARATOR, __DIR__);
unset($dirParts[count($dirParts)-1]);
$dirParts[] = 'php';
$dirParts[] = 'ParserIKData';
$dirParts[] = 'Actions';
$dirParts[] = 'include.php';
$logicIncludeFilePath = implode(DIRECTORY_SEPARATOR, $dirParts);

include_once $logicIncludeFilePath;

define('WATCH_GN', ParserIKData_Model_Protocol412::TYPE_GN);
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();