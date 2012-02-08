<?php
$dirParts = explode(DIRECTORY_SEPARATOR, __DIR__);
unset($dirParts[count($dirParts)-1]);
$dirParts[] = 'php';
$dirParts[] = 'ParserIKData';
$dirParts[] = 'Actions';
$dirParts[] = 'include.php';
$logicIncludeFilePath = implode(DIRECTORY_SEPARATOR, $dirParts);

include_once $logicIncludeFilePath;

$watchGn = ParserIKData_Model_Protocol412::TYPE_GN;

/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs();
$warehouse->loadElectionWatches('412', $watchGn);

$watchGateway = new ParserIKData_Gateway_Watch412();

$view = new stdClass();
$view->okrugs = ParserIKData_Model_Okrug::getAllOBjects();
$view->watches = ParserIKData_Model_Watch412::getAllObjects();

$view->totalUikCount = $watchGateway->getCount($watchGn);


$uikGateway = new ParserIKData_Gateway_UIK();
foreach ($view->okrugs as $okrug) {
    $uiks = $uikGateway->getForOkrug($okrug);
}

include 'tpl/index.tpl';