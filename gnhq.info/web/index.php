<?php
$dirParts = explode(DIRECTORY_SEPARATOR, __DIR__);
unset($dirParts[count($dirParts)-1]);
$dirParts[] = 'php';
$dirParts[] = 'ParserIKData';
$dirParts[] = 'Actions';
$dirParts[] = 'include.php';
$logicIncludeFilePath = implode(DIRECTORY_SEPARATOR, $dirParts);

include_once $logicIncludeFilePath;

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();
$warehouse->loadElectionWatches('412', 'GN');

$view = new stdClass();
$view->okrugs = ParserIKData_Model_Okrug::getAllOBjects();
$view->watches = ParserIKData_Model_Watch412::getAllObjects();

include 'tpl/index.tpl';