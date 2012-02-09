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

define('SELECTION_TYPE_DEFAULT', 'ALL');
define('SELECTION_TYPE_PROTOCOL', 'PROTOCOL');
define('SELECTION_TYPE_CLEAN', 'CLEAN');

define('DISPLAY_MODE_UIK', 'UIK');
define('DISPLAY_MODE_OIK', 'OIK');
define('DISPLAY_MODE_RIK', 'RIK');

define('GN_SITE', 'http://nabludatel.org/');