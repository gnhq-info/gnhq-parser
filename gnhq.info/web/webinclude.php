<?php
if (!defined('PROJECT_STARTED')) {
    die();
}

ini_set('register_globals', 0);
ini_set('display_errors', 0);


$dirParts = explode(DIRECTORY_SEPARATOR, __DIR__);
unset($dirParts[count($dirParts)-1]);
$dirParts[] = 'php';
define('ERROR_LOG_FILE', implode(DIRECTORY_SEPARATOR, $dirParts) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
define('FRONTEND_DIR', __DIR__);
define('TPL_DIR', FRONTEND_DIR . DIRECTORY_SEPARATOR . 'tpl');
$dirParts[] = 'ParserIKData';
$dirParts[] = 'Actions';
$dirParts[] = 'include.php';
$logicIncludeFilePath = implode(DIRECTORY_SEPARATOR, $dirParts);

ini_set('log_errors', 1);
ini_set('error_reporting', E_ALL);
ini_set('error_log', ERROR_LOG_FILE);


require_once $logicIncludeFilePath;

define('DEBUG', PHP_OS == 'WINNT' ? true : false);
define('AUTH_NEEDED', strtolower($_SERVER['HTTP_HOST']) == 'gnhq.info' ? true : false);

if (AUTH_NEEDED) {
    include('auth.php');
}


define('WATCH_GN', ParserIKData_Model_Protocol::TYPE_GN);
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();

define('SELECTION_TYPE_DEFAULT', 'ALL');
define('SELECTION_TYPE_PROTOCOL', 'PROTOCOL');
define('SELECTION_TYPE_CLEAN', 'CLEAN');
define('SELECTION_TYPE_DISCREPANCY', 'DISCR');
define('SELECTION_TYPE_REPORT', 'REPORT');

define('DISPLAY_MODE_UIK', 'UIK');
define('DISPLAY_MODE_OIK', 'OIK');
define('DISPLAY_MODE_RIK', 'RIK');

define('GN_SITE', 'http://nabludatel.org/');